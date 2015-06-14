<?php namespace App\Http\Controllers;
//use App\Http\Controllers;
use App\finalscore;
use App\detailed_score;
use App\quiz;
use App\User;
use Illuminate\Http\Request;
use Auth;

class GraphController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Graph Controller
	|--------------------------------------------------------------------------
	|
	| Shows different graphs depending on the input and the role of the logged in user
	|
	*/

	public function __construct()
	{
		$this->middleware('auth');
	}

        public function graph(Request $request)
        {
            //checking for the role of the logged in user
            if ((Auth::user()->role)=='teacher'){    
                
                $inputs=$request->all();
                //query databases for the data for the graphs
                $testids = finalscore::distinct()->lists('testid');
                $testids_det = detailed_score::distinct()->lists('testid');
                $testids_detall = quiz::distinct()->orderBy('testid','asc')->lists('testid');
                $stud_ids = finalscore::distinct()->lists('sid');
         
                // if the form input is test_id, pass the data to specific graph
                if (isset($inputs['test_id'])){
                    
                    $seltest=$inputs['test_id'];
                    $scores = finalscore::where('testid', $seltest)->get();
                    $marksTable = \Lava::DataTable();  
                    $marksTable ->addStringColumn('StudentID')
                                ->addNumberColumn('Individual');
                    foreach ($scores as $row){
                        $rowData = array($row['sid'], $row['mark']);
                        $marksTable->addRow($rowData);
                    }
                    
                    $linechart = \Lava::LineChart('myFancyChart'); 
                    $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Marks for test '.$seltest,
                                'vAxis' => \Lava::VerticalAxis(array(
                                'title' => 'Scores',
                                'maxValue'=> 100,
                                'minValue'=> 0
                                )),
                                'hAxis' => \Lava::HorizontalAxis(array(
                                'title' => 'student id',
                                )) 
                    ));
                    
                    return view('graphscores.teacher')->with('scores',$scores)
                    ->with('testids',$testids)
                    ->with('stud_ids',$stud_ids)
                    ->with('testid_det',$testids_det)
                    ->with('testid_detall',$testids_detall);
                }
                
                //if the input of the form is student id, graph all marks for student id
                if (isset($inputs['student_id'])){
                    
                    $selstuid=$inputs['student_id'];
                    $scores2 = finalscore::where('sid', $selstuid)->get();
                    $marksTable = \Lava::DataTable();  
                    $marksTable ->addStringColumn('TestID')
                                ->addNumberColumn('student_id'.$selstuid);
                    foreach ($scores2 as $row){
                        $rowData = array($row['testid'], $row['mark']);
                        $marksTable->addRow($rowData);
                    }
                    $linechart = \Lava::ColumnChart('myFancyChart2'); 
                    $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Marks for student (id '.$selstuid.') ',
                                'vAxis' => \Lava::VerticalAxis(array(
                                'title' => 'Scores',
                                'maxValue'=> 100,
                                'minValue'=> 0
                                )),
                                'hAxis' => \Lava::HorizontalAxis(array(
                                'title' => 'test id',
                                //'gridlines' => (array('count'=>4,'color'=>'grey'))
                                )) 
                            ));              
                    return view('graphscores.teacher')->with('scores2',$scores2)
                    ->with('testids',$testids)
                    ->with('stud_ids',$stud_ids)
                    ->with('testid_det',$testids_det)
                    ->with('testid_detall',$testids_detall);
                }
                
                //if form input is the test id for the sum of correct answers, create graph for this data
                if (isset($inputs['testsum_id'])){
                    
                    $seltest=$inputs['testsum_id'];
                    $qnumbers = detailed_score::distinct()->lists('qnumber');
                    for ($i=0; $i<sizeof($qnumbers);$i++){
                        $qscores = detailed_score::where('testid', $seltest)->where('qnumber', $qnumbers[$i])->get();
                        $npass=0;
                        foreach ($qscores as $row){$npass=$npass+$row['pass'];}
                        $totscore[$i]=$npass;
                    }
                
                    $marksTable = \Lava::DataTable();  
                    $marksTable ->addStringColumn('qnumber')
                                ->addNumberColumn('number_passed');
                    
                    for ($i=0; $i<sizeof($qnumbers);$i++){
                        $rowData = array($qnumbers[$i], $totscore[$i]);
                        $marksTable->addRow($rowData);
                    }
                    $linechart = \Lava::LineChart('myFancyChart3'); 
                    $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Sum of correct answers for test'.$seltest,
                                'vAxis' => \Lava::VerticalAxis(array(
                                'title' => 'Number of correct answers',
                                'minValue'=> 0
    
                                )),
                                'hAxis' => \Lava::HorizontalAxis(array(
                                'title' => 'question number',
                                //'gridlines' => (array('count'=>4,'color'=>'grey'))
                                )) 
                            ));              
                    
                    return view('graphscores.teacher')->with('qscores',$qscores)->with('testids',$testids)
                    ->with('stud_ids',$stud_ids)->with('testid_det',$testids_det)->with('testid_detall',$testids_detall);
                }
                
                //if the form input is the test to see the number of students taking the test, create the graph
                if (isset($inputs['test_taken'])){
                    
                    $seltest=$inputs['test_taken'];
                    $taken=array();
                    $nottaken=array();
                    $allstudents = User::distinct()->where('role', 'student')->lists('sid');
                    
                    for ($i=0; $i<sizeof($allstudents);$i++){
                        $checkstudent = finalscore::where('sid', $allstudents[$i])->where('testid', $seltest)->first();
                        if ($checkstudent==null){array_push($nottaken,'1');}
                        if ($checkstudent!==null){array_push($taken,'1');}
                    }    
                    $marksTable = \Lava::DataTable();  
                    $marksTable ->addStringColumn('status')
                    ->addNumberColumn('number of students');
                    $marksTable->addRow(array('Completed',count($taken)));
                    $marksTable->addRow(array('Not completed',count($nottaken)));
                    $linechart = \Lava::PieChart('myFancyChart4'); 
                    $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Percent of students who completed test ' .$seltest,
                            ));              
                    return view('graphscores.teacher')->with('taken_id',$taken)->with('testids',$testids)
                    ->with('stud_ids',$stud_ids)->with('testid_det',$testids_det)->with('testid_detall',$testids_detall);
                }
            }
        }
	public function index()
	{
            //query databases for data for the dropdown lists
            $testids = finalscore::distinct()->lists('testid');
            $testids_det = detailed_score::distinct()->lists('testid');
            $testids_detall = quiz::distinct()->orderBy('testid','asc')->lists('testid');                
            $stud_ids = finalscore::distinct()->lists('sid');
            // check if the authorized user is teacher, then show the teacher view with data    
            if ((Auth::user()->role)=='teacher'){
                return view('graphscores.teacher')->with('testids',$testids)
                ->with('stud_ids',$stud_ids)->with('testid_det',$testids_det)->with('testid_detall',$testids_detall);
            }
            
            // check if the authorized user is student then show the student view with data
            if ((Auth::user()->role)=='student'){
                $selstuid=Auth::user()->sid;
                $scores2 = finalscore::where('sid', $selstuid)->get();
                $marksTable = \Lava::DataTable();  
                $marksTable ->addStringColumn('TestID')
                        ->addNumberColumn('student_id'.$selstuid);
                foreach ($scores2 as $row)
                {
                    $rowData = array($row['testid'], $row['mark']);
                    $marksTable->addRow($rowData);
                }
                $linechart = \Lava::ColumnChart('myFancyChart2'); 
                $linechart->datatable($marksTable)
                            ->setOptions(array(
                                'title' => 'Marks for student (id '.$selstuid.') ',
                                'vAxis' => \Lava::VerticalAxis(array(
                                'title' => 'Scores',
                                'maxValue'=> 100,
                                'minValue'=> 0

                                )),
                                'hAxis' => \Lava::HorizontalAxis(array(
                                'title' => 'quiz id',
                                )) 
                            ));              
                return view('graphscores.index')->with('scores2',$scores2)->with('testids',$testids)
                    ->with('stud_ids',$stud_ids)->with('testid_det',$testids_det)->with('testid_detall',$testids_detall);
            }
        }
}
