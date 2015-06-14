<?php namespace App\Http\Controllers;
use App\Http\Controllers;
use App\finalscore;
use App\detailed_score;
use App\quiz;
use Illuminate\Http\Request;
use Auth;

class QuizController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| quiz Controller
	|--------------------------------------------------------------------------
	|
	| lets the student take the quiz and saves results
	|
	*/

	/**
	 * is only for authorized users
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
       
	public function index($id)
	{   
            //checks if authorized user is student
            if ((Auth::user()->role)=='student'){    
            // for the first question, set the question number
            if (!isset($qn)){
            $qn=0;}
            //gets the list of questions from database
            $questions = quiz::where('testid', $id)->lists('qtest');
            $question=quiz::where('qtest', $questions[$qn])->get();
            //returnes view with questions
            return view('quiz.index')->with('question',$question)->with('qn',$qn)->with('quizn',$id);
            }
	}
        //list the quizzes available
        public function listtests()
	{
            //checks if authorized user is student
            if ((Auth::user()->role)=='student'){    
                $tests = quiz::distinct()->orderBy('testid','asc')->lists('testid');
                // checks if the quiz was taken already
                for ($i=0; $i<sizeof($tests);$i++){
                    $checktest=finalscore::where('testid', $tests[$i])->where('sid',Auth::user()->sid)->first();
                    if (isset($checktest)){
                        $teststatus[$i]=1;
                    }
                    if (!isset($checktest)){
                        $teststatus[$i]=0;
                    }
                }          
                return view('quiz.listtests')->with('testids',$tests)->with('teststatus',$teststatus);
                }
            }
            //function which gives next question and saves students answers
        public function next(Request $request, $id){
            if ((Auth::user()->role)=='student'){    
            $inputs=$request->all();
            $qn=$inputs['qn'];
            //get all questions for the test
            $questions = quiz::where('testid', $id)->lists('qtest');
            if ($qn<sizeof($questions)){
            //select the current question entry to get correct answer
            $question=quiz::where('qtest', $questions[$qn])->get();        
            //check if input is correct and save it
            if ($inputs['qchoice']==$question[0]['correct']){
                $pass=1; 
            }
            if ($inputs['qchoice']!==$question[0]['correct']){
                $pass=0;
            }
            //saves the student answer
            $detailed_score=new detailed_score;
            $detailed_score->testid=$id;
            $detailed_score->sid=Auth::user()->sid;
            $detailed_score->qnumber=$qn+1;
            $detailed_score->pass=$pass;
            $detailed_score->save();
            $qn=$qn+1;
            //set next question
            if ($qn<sizeof($questions)){
            $question=quiz::where('qtest', $questions[$qn])->get();
                }
            if ($qn==sizeof($questions)){
                //get all answers for the test
                $answers = detailed_score::where('testid', $id)->where('sid', Auth::user()->sid)->get();
                $canswers = detailed_score::where('testid', $id)->where('sid', Auth::user()->sid)->where('pass', 1)->get();
                $final_score=(sizeof($canswers)/sizeof($answers))*100;
                //echo $final_score;
                //save marks to the final score table
                $finalscore=new finalscore;
                $finalscore->testid=$id;
                $finalscore->sid=Auth::user()->sid;
                $finalscore->mark=$final_score;
                $finalscore->save();
                //create chart to view correct answers
                $scores = detailed_score::where('testid', $id)->where('sid', Auth::user()->sid)->get();
                $marksTable = \Lava::DataTable();  
                $marksTable ->addStringColumn('QuestionNumber')
                    ->addNumberColumn('PassorFail');
                foreach ($scores as $row)
                {
                    $rowData = array($row['qnumber'], $row['pass']);
                    $marksTable->addRow($rowData);
                }
                $linechart = \Lava::LineChart('lookatmarks'); 
                $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Correct or not',
                                'vAxis' => \Lava::VerticalAxis(array(
                                'title' => 'Correct(1) Incorrect(0)'
                                )),
                                'hAxis' => \Lava::HorizontalAxis(array(
                                'title' => 'Question number',
                                )) 
                            ));        
                return view('quiz.finished')->with('finalmark',$final_score)->with('quizn',$id);
                }
            }
            return view('quiz.index')->with('question',$question)->with('qn',$qn)->with('quizn',$id);
            }
        }

}
