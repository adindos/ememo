<?php


App::uses('AppController', 'Controller');
require_once(APP . 'Vendor' . DS . 'PHPExcel' . DS . 'Classes'. DS . 'PHPExcel.php');

class ReportController extends AppController {

    public $uses = array('Company','Group','Setting','Department','FMemo','FMemoBudget','FRemark','FRemarkAssign','FRemarkFeedback','FReviewer','FStatus','FVendorAttachment','FActivities','Budget','BNewAmount','BItem','Staff','FMemoTo','Setting','Department','NfMemo','NfComment','NfMemoBudget','NfRemark','NfRemarkAssign','NfRemarkFeedback','NfReviewer','NfStatus','FActivities','BNewAmount','BItem','Staff','NfMemoTo','NfAttachment','UserNotification');

    public $layout = 'mems';

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index(){

    }

    public function budgetUtilizationReport(){
         $user = $this->getAuth();
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }
        $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));
        //to pass for submit button othr than the form
       
        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            
            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
               
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

                       
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>array('item')), 'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>array('department_name'))
                ));
           
            $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the budget utilization you want to view. </b>','flash.info');

    }

    public function budgetTransferReport(){
         $user = $this->getAuth();
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }
        $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));
        //to pass for submit button othr than the form
       

       
        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            
            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
               
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

                       
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>array('item')), 'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>array('department_name'))
                ));
           
            $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the budget transfer you want to view. </b>','flash.info');

    }

    public function unbudgetedReport(){
         $user = $this->getAuth();
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }
        $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));

        //to pass for submit button othr than the form
       
        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
               
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

                       
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                ));
           
            $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the unbudgeted report you want to view. </b>','flash.info');

    }

    public function financialMemoReport(){
        $user = $this->getAuth();
        if(!$user['Role']['report_financial_memo']){
            throw new ForbiddenException();
        }

        $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //to pass for submit button othr than the form
        if (!empty($this->request->params['named']['div'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['division_id']=$this->request->params['named']['div']; 
        }

        if (!empty($this->request->params['named']['dept'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['department_id']=$this->request->params['named']['dept']; 
        }

        if (!empty($this->request->params['named']['user'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['user_id']=$this->request->params['named']['user']; 
        }

        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            $groupData='';
            $deptData='';
            $userData='';
            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
                if (!empty($this->request->data['Filter']['division_id']))
                    $groupData = $this->request->data['Filter']['division_id'];
                if (!empty($this->request->data['Filter']['department_id']))
                    $deptData = $this->request->data['Filter']['department_id'];
                if (!empty($this->request->data['Filter']['user_id']))
                    $userData = $this->request->data['Filter']['user_id'];
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

           
            if(!empty($groupData)){
                array_push($conditions,array('Department.group_id'=>$groupData));
            }
            if(!empty($deptData)){
                array_push($conditions,array('FMemo.department_id'=>$deptData));
            }
            if(!empty($userData)){
                array_push($conditions,array('FMemo.user_id'=>$userData));
            }
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->FMemo->contain(array('User','Department'=>array('fields'=>array('department_name')),
            'Department.Group'=>array('fields'=>array('group_name'))));
           
            $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the financial memo you want to view. </b>','flash.info');

        $this->set('groups',$this->Group->find('list',array('order'=>'group_name Asc')));
        $this->set('departments',$this->Department->find('list',array('order'=>'code_name Asc')));
        $this->set('allUsers',$this->User->find('list',array('order'=>'staff_name Asc')));
    }

    public function memoReport(){
        $user = $this->getAuth();
        if(!$user['Role']['report_memo']){
            throw new ForbiddenException();
        }
        $conditions = array('NfMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //to pass for submit button othr than the form
        if (!empty($this->request->params['named']['div'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['division_id']=$this->request->params['named']['div']; 
        }

        if (!empty($this->request->params['named']['dept'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['department_id']=$this->request->params['named']['dept']; 
        }

        if (!empty($this->request->params['named']['user'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['user_id']=$this->request->params['named']['user']; 
        }

        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            $groupData='';
            $deptData='';
            $userData='';
            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
                if (!empty($this->request->data['Filter']['division_id']))
                    $groupData = $this->request->data['Filter']['division_id'];
                if (!empty($this->request->data['Filter']['department_id']))
                    $deptData = $this->request->data['Filter']['department_id'];
                if (!empty($this->request->data['Filter']['user_id']))
                    $userData = $this->request->data['Filter']['user_id'];
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

           
            if(!empty($groupData)){
                array_push($conditions,array('Department.group_id'=>$groupData));
            }
            if(!empty($deptData)){
                array_push($conditions,array('NfMemo.department_id'=>$deptData));
            }
            if(!empty($userData)){
                array_push($conditions,array('NfMemo.user_id'=>$userData));
            }
            if(!empty($fromData)){
                array_push($conditions,array('NfMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('NfMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->NfMemo->contain(array('User','Department'=>array('fields'=>array('department_name')),
            'Department.Group'=>array('fields'=>array('group_name'))));
           
            $memo=$this->NfMemo->find('all',array('conditions'=> $conditions,'order'=>array('NfMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the non-financial memo you want to view. </b>','flash.info');

        $this->set('groups',$this->Group->find('list',array('order'=>'group_name Asc')));
        $this->set('departments',$this->Department->find('list',array('order'=>'code_name Asc')));
        $this->set('allUsers',$this->User->find('list',array('order'=>'staff_name Asc')));
    }
    public function exportUtilization($name=null){


        $user = $this->getAuth();
        // Check user role
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }

       $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));

        $fromData='';
        $toData='';
        
           
        if (!empty($this->request->data['Filter']['date_from']))
            $fromData = $this->request->data['Filter']['date_from'];
        if (!empty($this->request->data['Filter']['date_to']))
            $toData = $this->request->data['Filter']['date_to'];
             
      
                   
        if(!empty($fromData)){
            array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
        }
        if(!empty($toData)){
            array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
        }

         $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>array('item')), 'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>array('department_name'))
                ));
           
        $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
        if ( $name == 'pdf' ){
            $this->layout = 'mems-pdf-report';
            $this->pdfConfig = array(
                'orientation'=>'landscape',
                'pageSize'=>'a4',
                'filename'=>'budget_utilization_report_'.date('dmy').'.pdf',
                'download'=>true,
            );
        }
        else if ( $name == 'excel' ){
            $this->layout = 'mems-excel';
            $this->excelConfig =  array(
                'filename' => 'budget_utilization_report_'.date('dmy').'.xlsx'
            );
        }

       
       $this->set('memo',$memo);
       
       $this->render('budget_utilization');
    }
    public function exportUnbudgeted($name=null){


        $user = $this->getAuth();
        // Check user role
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }

       $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));

        $fromData='';
        $toData='';
        
           
        if (!empty($this->request->data['Filter']['date_from']))
            $fromData = $this->request->data['Filter']['date_from'];
        if (!empty($this->request->data['Filter']['date_to']))
            $toData = $this->request->data['Filter']['date_to'];
             
      
                   
        if(!empty($fromData)){
            array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
        }
        if(!empty($toData)){
            array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
        }

         $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                
                ));
           
        $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
        if ( $name == 'pdf' ){
            $this->layout = 'mems-pdf-report';
            $this->pdfConfig = array(
                'orientation'=>'landscape',
                'pageSize'=>'a4',
                'filename'=>'unbudgeted_report_'.date('dmy').'.pdf',
                'download'=>true,
            );
        }
        else if ( $name == 'excel' ){
            $this->layout = 'mems-excel';
            $this->excelConfig =  array(
                'filename' => 'unbudgeted_report_'.date('dmy').'.xlsx'
            );
        }

       
       $this->set('memo',$memo);
       
       $this->render('unbudgeted');
    }
    public function exportTransfer($name=null){


        $user = $this->getAuth();
        // Check user role
        if(!$user['Role']['report_budget']){
            throw new ForbiddenException();
        }

       $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');
        //if not super admin or finance admin, show only dept specific
        if (!($user['role_id']==17 || $user['finance']))
            array_push($conditions,array('FMemo.department_id'=>$user['department_id']));

        $fromData='';
        $toData='';
        
           
        if (!empty($this->request->data['Filter']['date_from']))
            $fromData = $this->request->data['Filter']['date_from'];
        if (!empty($this->request->data['Filter']['date_to']))
            $toData = $this->request->data['Filter']['date_to'];
             
      
                   
        if(!empty($fromData)){
            array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
        }
        if(!empty($toData)){
            array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
        }

         $this->FMemo->contain(array('FMemoBudget','User',
                'Department'=>array('fields'=>array('department_name')),
                'FMemoBudget.BItemAmount.Item'=>array('fields'=>array('item')), 
                'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>array('item')), 'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>array('department_name'))
                ));
           
        $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
        if ( $name == 'pdf' ){
            $this->layout = 'mems-pdf-report';
            $this->pdfConfig = array(
                'orientation'=>'landscape',
                'pageSize'=>'a4',
                'filename'=>'budget_transfer_report_'.date('dmy').'.pdf',
                'download'=>true,
            );
        }
        else if ( $name == 'excel' ){
            $this->layout = 'mems-excel';
            $this->excelConfig =  array(
                'filename' => 'budget_transfer_report_'.date('dmy').'.xlsx'
            );
        }

       
       $this->set('memo',$memo);
       
       $this->render('budget_transfer');
    }
    public function exportFMemo($name=null){


        $user = $this->getAuth();
        // Check user role
        if(!$user['Role']['report_financial_memo']){
            throw new ForbiddenException();
        }

        $conditions = array('FMemo.submission_no NOT'=>0,'memo_status'=>'approved');

        $groupData='';
        $deptData='';
        $userData='';
        $fromData='';
        $toData='';
       
        if (!empty($this->request->data['Filter']['division_id']))
            $groupData = $this->request->data['Filter']['division_id'];
        if (!empty($this->request->data['Filter']['department_id']))
            $deptData = $this->request->data['Filter']['department_id'];
        if (!empty($this->request->data['Filter']['user_id']))
            $userData = $this->request->data['Filter']['user_id'];
        if (!empty($this->request->data['Filter']['date_from']))
            $fromData = $this->request->data['Filter']['date_from'];
         if (!empty($this->request->data['Filter']['date_to']))
            $toData = $this->request->data['Filter']['date_to'];
       
        if(!empty($groupData)){
            array_push($conditions,array('Department.group_id'=>$groupData));
        }
        if(!empty($deptData)){
            array_push($conditions,array('FMemo.department_id'=>$deptData));
        }
        if(!empty($userData)){
            array_push($conditions,array('FMemo.user_id'=>$userData));
        }
        if(!empty($fromData)){
            array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
        }
        if(!empty($toData)){
            array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
        }

         $this->FMemo->contain(array('User','Department'=>array('fields'=>array('department_name')),
        'Department.Group'=>array('fields'=>array('group_name'))));
       
        $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));

        if ( $name == 'pdf' ){
            $this->layout = 'mems-pdf-report';
            $this->pdfConfig = array(
                'orientation'=>'landscape',
                'pageSize'=>'a4',
                'filename'=>'financial_memo_report_'.date('dmy').'.pdf',
                'download'=>true,
            );
        }
        else if ( $name == 'excel' ){
            $this->layout = 'mems-excel';
            $this->excelConfig =  array(
                'filename' => 'financial_memo_report_'.date('dmy').'.xlsx'
            );
        }

       
       $this->set('memo',$memo);
       
        $this->render('fmemo');
    }


    public function exportNFMemo($name=null){

        $user = $this->getAuth();
        // Check user role
        if(!$user['Role']['report_memo']){
            throw new ForbiddenException();
        }

        $conditions = array('NfMemo.submission_no NOT'=>0,'memo_status'=>'approved');

        $groupData='';
        $deptData='';
        $userData='';
        $fromData='';
        $toData='';
       
        if (!empty($this->request->data['Filter']['division_id']))
            $groupData = $this->request->data['Filter']['division_id'];
        if (!empty($this->request->data['Filter']['department_id']))
            $deptData = $this->request->data['Filter']['department_id'];
        if (!empty($this->request->data['Filter']['user_id']))
            $userData = $this->request->data['Filter']['user_id'];
        if (!empty($this->request->data['Filter']['date_from']))
            $fromData = $this->request->data['Filter']['date_from'];
         if (!empty($this->request->data['Filter']['date_to']))
            $toData = $this->request->data['Filter']['date_to'];
       
        if(!empty($groupData)){
            array_push($conditions,array('Department.group_id'=>$groupData));
        }
        if(!empty($deptData)){
            array_push($conditions,array('NfMemo.department_id'=>$deptData));
        }
        if(!empty($userData)){
            array_push($conditions,array('NfMemo.user_id'=>$userData));
        }
        if(!empty($fromData)){
            array_push($conditions,array('NfMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
        }
        if(!empty($toData)){
            array_push($conditions,array('NfMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
        }

         $this->NfMemo->contain(array('User','Department'=>array('fields'=>array('department_name')),
        'Department.Group'=>array('fields'=>array('group_name'))));
       
        $memo=$this->NfMemo->find('all',array('conditions'=> $conditions,'order'=>array('NfMemo.created'=>'Desc')));

        if ( $name == 'pdf' ){
            $this->layout = 'mems-pdf-report';
            $this->pdfConfig = array(
                'orientation'=>'landscape',
                'pageSize'=>'a4',
                'filename'=>'nonfinancial_memo_report_'.date('dmy').'.pdf',
                'download'=>true,
            );
        }
        else if ( $name == 'excel' ){
            $this->layout = 'mems-excel';
            $this->excelConfig =  array(
                'filename' => 'nonfinancial_memo_report_'.date('dmy').'.xlsx'
            );
        }

       
       $this->set('memo',$memo);
       
        $this->render('nfmemo');
    }

}
