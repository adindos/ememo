
<?php

	App::uses('AppController', 'Controller');

	class ArchiveController extends AppController{
		public $layout = 'mems';

		var $uses = array('User','BArchive','Company');



		/*
		 * Renders all settings data displayed @ budgetarchive.ctp
		 *
		 * @param () 
		 * @return ()
		 *
		 * latest modified 8/March @ Faridi
		 */	
		public function budgetArchive(){


			$user = $this->getAuth();
			$this->User->contain(array('Role'));
			$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));


			if($user_data['Role']['budget_archive'] == 0){

				// return $this->redirect(array('controller' => 'Error' ,'action'=>'error500'));
				// $this->layout = 'ajax';
				throw new ForbiddenException();
			}




			$this->BArchive->contain(array('Company'));
			$allArchives = $this->BArchive->find('all');
			$this->set('allArchives',$allArchives);


			$this->Company->recursive = -1;
			$companies = $this->Company->find('all');
			$this->set('allcompanies',$companies);

		}



		/*
		 * Provides the functionality control the addation of new bduget archive to the system.
		 *
		 * @input ($this->request->data): the budget archive to be added.
		 * @output Save changes into the database.
		 *
		 * latest modified 16/March @ Faridi
		 */

		public function addBudgetArchive()
		{
			if($this->request->is('post')){


				$data = $this->request->data;
				// debug($data);
				// exit;

				// validation 
				if($data['BArchive']['company_id'] == NULL || $data['BArchive']['company_id'] == '' || empty($data['BArchive']['company_id'])){ // not empty
					$this->Session->setFlash('Error. Archive not saved. Company name cannot be empty','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}



				if($data['BArchive']['year'] == NULL || $data['BArchive']['year'] == '' || empty($data['BArchive']['year'])){ // not empty
					$this->Session->setFlash('Error. Archive not saved. year cannot be empty','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}



				//Upload the files
				if(!empty($this->request->data['BArchive']['filename'])){

					$file = $this->request->data['BArchive']['filename'];


					$typeOK = false;
					// list of permitted file types, 
					$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

					$filename = null;

					
					// check filetype is ok
					foreach($permitted as $type) {
						if($type == $file['type']) {
							$typeOK = true;
							break;
						}
					}


					if(!$typeOK){
						$this->Session->setFlash('Error. New budget archive not saved, please check the uploaded files. Only PDF, Excel and Word files accepted.','flash.error');
						return $this->redirect(array('action'=>'budgetArchive'));
					}


					if($file['size'] > 10000000)
					{
						$this->Session->setFlash('Error. New budget archive not saved, please check the uploaded file. File must not exceed MB 10','flash.error');
						return $this->redirect(array('action'=>'budgetArchive'));

					}


					
					if(!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])){

								$filename = time().'___'.basename($file['name']); 
								echo($filename);

								if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.'fbudget-attachment'.DS.$filename)){

									$this->request->data['BArchive']['filename'] = $filename;
 									$this->BArchive->create();
  
								}

					}


				}


				if($this->BArchive->save($this->request->data)){
					$this->Session->setFlash('New budget archive saved','flash.success');
					return $this->redirect(array('action'=>'budgetArchive'));

				}else{
					$this->Session->setFlash('Error. New budget archive not saved','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}
			}
			
		}


		/*
		 * Provides the functionality to control the editing of budget archive to the system.
		 *
		 * @input ($this->request->data): the company data to be edited.
		 * @output Save changes into the database.
		 *
		 * latest modified 9/March @ Faridi
		 */

		public function editBudgetArchive()
		{
			if($this->request->is('post')){
				$user = $this->getAuth();


				$data = $this->request->data;
				// debug($data);
				// exit;

				// validation 
				if($data['BArchive']['company_id'] == NULL || $data['BArchive']['company_id'] == '' || empty($data['BArchive']['company_id'])){ // not empty
					$this->Session->setFlash('Error. Archive not saved. Company name cannot be empty','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}



				if($data['BArchive']['year'] == NULL || $data['BArchive']['year'] == '' || empty($data['BArchive']['year'])){ // not empty
					$this->Session->setFlash('Error. Archive not saved. year cannot be empty','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}



 				$archive_id = $this->request->data['BArchive']['archive_id'];
				$this->BArchive->id = $archive_id;

				if($this->BArchive->save($this->request->data)){
					$this->Session->setFlash('Changes saved','flash.success');
					return $this->redirect(array('action'=>'budgetArchive'));
				
				}else{
					$this->Session->setFlash('Error. Changes not saved','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}
			}
			
		}


		/*
		 * Provides the functionality control the deletion of archives from the system.
		 *
		 * @input ($this->request->data): archive data to be delete.
		 * @output Save changes into the database.
		 *
		 * latest modified 8/March @ Faridi
		 */

		public function deleteArchiveBudget($archive_id)
		{
			if($this->request->is('post')){
				$user = $this->getAuth();
			
				$this->BArchive->id = $archive_id;
				if($this->BArchive->delete($archive_id)){
					$this->Session->setFlash('Budget archive deleted','flash.success');
					return $this->redirect(array('action'=>'budgetArchive'));
				
				}else{
					$this->Session->setFlash('Error. Budget archive not deleted','flash.error');
					return $this->redirect(array('action'=>'budgetArchive'));

				}
			}
			
		}


		/*
		 * Provides the functionality control the download of archive budget.
		 *
		 * @input ($this->request->data): archive data to be delete.
		 * @output Save changes into the database.
		 *
		 * latest modified 9/March @ Faridi
		 */

		public function downloadArchiveBudget($archive_id)
		{

			$this->BArchive->recursive = -1;
			$archive = $this->BArchive->find('first', array('conditions' => array('archive_id' => $archive_id)));
			$path = $archive['BArchive']['filename'];
			$path = WWW_ROOT.'files'.DS.'fbudget-attachment'.DS.$path;

			$this->response->file($path, array('download' => true));
			return $this->response;
			
		}



		public function view($id = null){
			
			// $this->autoRender = false;

			$this->BArchive->contain(array('Company'));
			$allArchives = $this->BArchive->find('all');
			$this->set('allArchives',$allArchives);

		
			$this->layout = 'default';
			$this->pdfConfig = array(
 				'filename'=>'testpdf.pdf',
 			);

			
		}

 


}