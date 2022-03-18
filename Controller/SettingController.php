<?php



App::uses('AppController', 'Controller');

class SettingController extends AppController{
	public $layout = 'mems';


	var $uses = array('User','Role','Company','Group','Department','Setting','Item');

	/*
	 * Renders all settings data displayed @ index.ctp
	 *
	 * @param () 
	 * @return ()
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function index(){


		$user = $this->getAuth();

		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));


		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		$this->User->contain(array('Department','Role','Department.Group','Department.Group.Company'));
		$users = $this->User->find('all');
		$this->set('allusers',$users);

		$this->Role->recursive = -1;
		$roles = $this->Role->find('all');
		$this->set('allroles',$roles);

		$this->Company->recursive = -1;
		$companies = $this->Company->find('all');
		$this->set('allcompanies',$companies);

		$this->Group->recursive = 0;
		$groups = $this->Group->find('all');
		$this->set('allgroups',$groups);

		$this->Department->contain(array('Group','Group.Company')) ;
		$departments = $this->Department->find('all');
		$this->set('alldepartments',$departments);


		$this->Setting->recursive = 0;
		$setting = $this->Setting->find('first');
		$this->set('reviewsettings',$setting);


		// $this->BCategory->recursive = -1;
		// $categories = $this->BCategory->find('all',array('order' => 'category ASC'));
		// $this->set('allCategories', $categories);


		$this->Item->recursive = 0;
		$items = $this->Item->find('all',array('order' => 'Item.item_code ASC'));
		$this->set('allitems', $items);
		$itemList = $this->Item->find('list',array('order' => 'Item.item_code ASC'));
		$this->set('itemList', $itemList);
		
	}



	/********** Users Settings ****************/

	/*
	 * Provides the functionality control the addation of new users to the system.
	 *
	 * @input ($this->request->data): the user's data to be added.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */

	public function addUser()
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){

			$this->User->create();

		
			$data = $this->request->data;


			//validation

			if(empty($data['User']['designation'])||empty($data['User']['staff_id'])||empty($data['User']['staff_name'])||empty($data['User']['email_address'])||empty($data['User']['role_id'])||empty($data['User']['company_id'])||empty($data['User']['group_id'])||empty($data['User']['department_id'])){ // not empty

				$this->Session->setFlash('Error. New user not saved. Please fill in all fields denoted with * .','flash.error');
				return $this->redirect(array('action'=>'index'));

			}

			$data['User']['status'] = "disabled";
			$data['User']['email'] = rand()+rand();


			// add user
			if($this->User->save($data)){
				$this->Session->setFlash('New user saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New user not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}

	/*
	 * Provides the functionality control disabling a user from the system.
	 *
	 * @input user_id
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function disableUser($user_id)
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){
			$user = $this->getAuth();
		
			$this->User->id = $user_id;

			if($this->User->saveField('status', "disabled")){
				$this->Session->setFlash('User Disabled','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. User was not disabled.','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


		/*
	 * Provides the functionality control disabling a user from the system.
	 *
	 * @input user_id
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function enableUser($user_id)
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}





		if($this->request->is('post')){
			$user = $this->getAuth();

		
			$this->User->id = $user_id;

			if($this->User->saveField('status', "enabled")){
				$this->Session->setFlash('User Enabled','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. User was not endabled.','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/*
	 * Provides the functionality to control the editing of users to the system.
	 *
	 * @input ($this->request->data): the user data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */

	public function editUser()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){
			$user = $this->getAuth();

			$data = $this->request->data;


			//validation
			if(empty($data['User']['designation'])||empty($data['User']['staff_id'])||empty($data['User']['staff_name'])||empty($data['User']['email_address'])||empty($data['User']['role_id'])||empty($data['User']['company_id'])||empty($data['User']['group_id'])||empty($data['User']['department_id'])){ // not empty

				$this->Session->setFlash('Error. New user not saved. Please fill in all fields denoted with * .','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
			


 			$user_id = $this->request->data['User']['user_id'];
			$this->User->id = $user_id;

			// $data['User']['email'] = $data['User']['email_address'];


			if($this->User->save($data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Changes not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}



	/********** Role Settings ****************/

	/*
	 * Provides the functionality control the addation of new roles to the system.
	 *
	 * @input ($this->request->data): the roles data to be added.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */

	public function addRole()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}





		if($this->request->is('post')){


			$data = $this->request->data;
			

			// validation 
			if($data['Role']['role_name'] == NULL || $data['Role']['role_name'] == '' || empty($data['Role']['role_name'])){ // not empty

				$this->Session->setFlash('Error. New role not saved. Role Name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			// $this->debugme($this->request->data);exit;	
			$this->Role->create();
			if($this->Role->save($this->request->data)){
				$this->Session->setFlash('New role saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New role not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}

		/*
	 * Provides the functionality control the deletion of groups from the system.
	 *
	 * @input ($this->request->data): the company data to be delete.
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function deleteRole($role_id)
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){
			$user = $this->getAuth();
		

			$this->Role->id = $role_id;
			if($this->Role->delete($role_id)){
				$this->Session->setFlash('Role deleted','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Role not deleted. Please check that the role is not used by any user and try again','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/*
	 * Provides the functionality to control the editing of roles to the system.
	 *
	 * @input ($this->request->data): the role data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */

	public function editRole()
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){
			$user = $this->getAuth();



			$data = $this->request->data;
			// debug($data);
			// exit;


			if(empty($data['Role']['role_name'])){ // not empty

				$this->Session->setFlash('Error. Role changes not saved. Role Name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			// $this->debugme($this->request->data);exit;
			$role_id = $this->request->data['Role']['role_id'];
			$this->Role->id = $role_id;

			if($this->Role->save($this->request->data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Changes not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/********** Company Settings ****************/

	/*
	 * Provides the functionality control the addation of new companies to the system.
	 *
	 * @input ($this->request->data): the company data to be added.
	 * @output Save changes into the database.
	 *
	 * latest modified 12/Feb @ Faridi
	 */

	public function addCompany()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){
			$user = $this->getAuth();


			$data = $this->request->data;
			// debug($data);
			// exit;


			// validation 
			if(empty($data['Company']['company'])){ // not empty
				$this->Session->setFlash('Error. New Company not saved. Company name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			$this->Company->create();
			if($this->Company->save($this->request->data)){
				$this->Session->setFlash('New company saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New company not saved. Please input valid data','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}

	/*
	 * Provides the functionality to control the editing of companies to the system.
	 *
	 * @input ($this->request->data): the company data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 12/Feb @ Faridi
	 */

	public function editCompany()
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){
			$user = $this->getAuth();



			$data = $this->request->data;
			// debug($data);
			// exit;


			// validation 
			if(empty($data['Company']['company'])){ // not empty
				$this->Session->setFlash('Error. Company not saved. Company name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			// $this->debugme($this->request->data);exit;
			$company_id = $this->request->data['Company']['id'];
			$this->Company->id = $company_id;

			if($this->Company->save($this->request->data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Changes not saved. Please input valid data','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/*
	 * Provides the functionality control the deletion of companies from the system.
	 *
	 * @input ($this->request->data): the company data to be delete.
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function deleteCompany($company_id)
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}


		if($this->request->is('post')){
			$user = $this->getAuth();
		
		
			$this->Company->id = $company_id;
			if($this->Company->delete($company_id)){
				$this->Session->setFlash('Company deleted','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Company not deleted. Please check that the company is not used by any Group and try again','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}



	/********** Group Settings *************/


	/*
	 * Provides the functionality control the adding new groups to the system.
	 *
	 * @input ($this->request->data): the group data to be added.
	 * @output Save changes into the database.
	 *
	 * latest modified 13/Feb @ Faridi
	 */

	public function addGroup()
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){


			$data = $this->request->data;
			// debug($data);
			// exit;

			// validation 
			if(empty($data['Group']['company_id'])){ // not empty
				$this->Session->setFlash('Error. New Group not saved. Company name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			if($data['Group']['group_name'] == NULL || $data['Group']['group_name'] == '' || empty($data['Group']['group_name'])){ // not empty
				$this->Session->setFlash('Error. New Group not saved. Group name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
	
			$this->Group->create();
			if($this->Group->save($this->request->data)){
				$this->Session->setFlash('New Group saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New group not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/*
	 * Provides the functionality control the deletion of groups from the system.
	 *
	 * @input ($this->request->data): the company data to be delete.
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function deleteGroup($group_id)
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}


		if($this->request->is('post')){
			$user = $this->getAuth();
		
			$this->Group->id = $group_id;
			if($this->Group->delete($group_id)){
				$this->Session->setFlash('Group deleted','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Group not deleted. Please check that the group is not used by any Department and try again','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/*
	 * Provides the functionality to control the editing of companies to the system.
	 *
	 * @input ($this->request->data): the company data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 13/Feb @ Faridi
	 */

	public function editGroup()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}




		if($this->request->is('post')){
			$user = $this->getAuth();

			$data = $this->request->data;
			// debug($data);
			// exit;


			// validation 
			if(empty($data['Group']['company_id'])){ // not empty
				$this->Session->setFlash('Error. Group not saved. Company name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			if(empty($data['Group']['group_name'])){ // not empty
				$this->Session->setFlash('Error. Group not saved. Group name cannot be empty','flash.error');
				return $this->redirect(array('action'=>'index'));

			}

			// $this->debugme($this->request->data);exit;
			$group_id = $this->request->data['Group']['group_id'];
			$this->Group->id = $group_id;

			if($this->Group->save($this->request->data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Group changes not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	/********** Department Settings *************/



	/*
	 * Provides the functionality control the adding of new departments to the system.
	 *
	 * @input ($this->request->data): the department data to be edited.
	 * @output Save changes into the department.
	 *
	 * latest modified 15/Feb @ Faridi
	 */


	public function addDepartment()
	{


		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){



			$data = $this->request->data;
			// debug($data);
			// exit;

			// validation 
			if(empty($data['Department']['company_id'])||empty($data['Department']['group_id'])||empty($data['Department']['department_name'])||empty($data['Department']['total_staff'])||empty($data['Department']['department_type'])||empty($data['Department']['department_shortform'])){ // not empty
				$this->Session->setFlash('Error. New Department not saved. Please fill in all fields denoted with * .','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
			
			$this->Department->create();
			if($this->Department->save($this->request->data)){
				$this->Session->setFlash('New department saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New department not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}



	/*
	 * Provides the functionality control the deletion of departments from the system.
	 *
	 * @input ($this->request->data): the department data to be delete.
	 * @output Save changes into the database.
	 *
	 * latest modified 6/March @ Faridi
	 */

	public function deleteDepartment($department_id)
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){
			$user = $this->getAuth();
		
			$this->Department->id = $department_id;
			if($this->Department->delete($department_id)){
				$this->Session->setFlash('Department deleted','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Department not deleted. Please check that the department is not used by any user or budget and try again','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}



	/*
	 * Provides the functionality to control the editing existing departments in the system.
	 *
	 * @input ($this->request->data): the deaprtment data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */


	public function editDepartment()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		if($this->request->is('post')){
			$user = $this->getAuth();

			$data = $this->request->data;
			// debug($data);
			// exit;

			// validation 
			if(empty($data['Department']['company_id'])||empty($data['Department']['group_id'])||empty($data['Department']['department_name'])||empty($data['Department']['total_staff'])||empty($data['Department']['department_type'])||empty($data['Department']['department_shortform'])){ // not empty
				$this->Session->setFlash('Error. New Department not saved. Please fill in all fields denoted with * .','flash.error');
				return $this->redirect(array('action'=>'index'));

			}



			// $this->debugme($this->request->data);exit;
			$department_id = $this->request->data['Department']['department_id'];
			$this->Department->id = $department_id;

			if($this->Department->save($this->request->data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Department changes not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}


	

	/********** Memo and Budget Settings *************/


	/*
	 * Provides the functionality control the adding of new items to the system.
	 *
	 * @input ($this->request->data): the item data to be added.
	 * @output Save changes into the department.
	 *
	 * latest modified 6/March @ Faridi
	 */


	public function addItem()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}

		if($this->request->is('post')){

			$data = $this->request->data;
			// debug($data);
			// exit;

			// validation 
			if(empty($data['Item']['item_code'])||empty($data['Item']['item'])){ // not empty
				$this->Session->setFlash('Error. Item not saved. Please fill in all fields denoted with * .','flash.error');
				return $this->redirect(array('action'=>'index'));

			}


			$this->Item->create();
			if($this->Item->save($this->request->data)){
				$this->Session->setFlash('New item saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. New item not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}



	/*
	 * Provides the functionality control the adding of new category to the system.
	 *
	 * @input ($this->request->data): the category data to be added.
	 * @output Save changes into the department.
	 *
	 * latest modified 6/March @ Faridi
	 */


	// public function addCategory()
	// {

	// 	$user = $this->getAuth();
	// 	$this->User->contain(array('Role'));
	// 	$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

	// 	if($user_data['Role']['settings'] == 0){

	// 		throw new ForbiddenException();
	// 	}



	// 	if($this->request->is('post')){

	// 		$data = $this->request->data;
	// 		// debug($data);
	// 		// exit;

	// 		// validation 
	// 		if($data['BCategory']['category'] == NULL || $data['BCategory']['category'] == '' || empty($data['BCategory']['category'])){ // not empty
	// 			$this->Session->setFlash('Error. New Category not saved. Category name cannot be empty','flash.error');
	// 			return $this->redirect(array('action'=>'index'));

	// 		}


				
	// 		$this->BCategory->create();
	// 		if($this->BCategory->save($this->request->data)){
	// 			$this->Session->setFlash('New category saved','flash.success');
	// 			return $this->redirect(array('action'=>'index'));
			
	// 		}else{
	// 			$this->Session->setFlash('Error. New category not saved','flash.error');
	// 			return $this->redirect(array('action'=>'index'));

	// 		}
	// 	}
		
	// }




	/*
	 * Provides the functionality to control the editing existing Memo and Budget Settings in the system.
	 *
	 * @input ($this->request->data): the Memo and Budget Settings data to be edited.
	 * @output Save changes into the database.
	 *
	 * latest modified 16/Feb @ Faridi
	 */


	public function editReviewSettings()
	{

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}


		if($this->request->is('post')){
			$user = $this->getAuth();

			$data = $this->request->data;
			// debug($data);
			// exit;

			// validation 
			if($data['Setting']['max_review_day_budget'] == NULL || $data['Setting']['max_review_day_budget'] == '' || empty($data['Setting']['max_review_day_budget'])){ // not empty
				$this->Session->setFlash('Error. Changes not saved. Budget Maximum Review Day cannot be empty and must be a number','flash.error');
				return $this->redirect(array('action'=>'index'));

			}



			if($data['Setting']['max_review_day_memo'] == NULL || $data['Setting']['max_review_day_memo'] == '' || empty($data['Setting']['max_review_day_memo'])){ // not empty
				$this->Session->setFlash('Error. Changes not saved. Memo Maximum Review Day cannot be empty and must be a number','flash.error');
				return $this->redirect(array('action'=>'index'));

			}




			$this->Setting->id = 1;

			if($this->Setting->save($this->request->data)){
				$this->Session->setFlash('Changes saved','flash.success');
				return $this->redirect(array('action'=>'index'));
			
			}else{
				$this->Session->setFlash('Error. Setting changes not saved','flash.error');
				return $this->redirect(array('action'=>'index'));

			}
		}
		
	}




	/***************** Functions for Ajax *****************/


	/*
	 * Provides the functionality to retrieve groups list based on company id
	 *
	 * @input: comopnay id
	 * @output: list of groups based on company id
	 *
	 * latest modified 8/March @ Faridi
	 */


	public function getGroupList(){

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}



		$this->autoRender = false;

		if($this->request->is('post')){

			$company_id = $this->request->data['Company'];
			$this->Group->recursive = -1;
			$groups = $this->Group->find('all', array('conditions' => array('company_id' => $company_id),'fields' => array('Group.group_id','Group.group_name')));

			$groupsList = array();

			foreach ($groups as $key => $group) {

				$groupsList[$group['Group']['group_id']] = $group['Group']['group_name'];

			}

			$groups = json_encode($groupsList);

			return $groups;		
		}
		


	} 



	/*
	 * Provides the functionality to retrieve departments list based on group id
	 *
	 * @input: group id
	 * @output: list of groups based on company id
	 *
	 * latest modified 8/March @ Faridi
	 */


	public function getDepartmentList(){

		$user = $this->getAuth();
		$this->User->contain(array('Role'));
		$user_data = $this->User->find('first', array('conditions' => array('user_id' => $user['user_id'])));

		if($user_data['Role']['settings'] == 0){

			throw new ForbiddenException();
		}

		

		$this->autoRender = false;

		if($this->request->is('post')){

			$group_id = $this->request->data['Group'];
			$this->Department->recursive = -1;
			$departments = $this->Department->find('all', array('conditions' => array('group_id' => $group_id),'fields' => array('Department.department_id','Department.department_name')));

			$departmentList = array();

			foreach ($departments as $key => $department) {

				$departmentList[$department['Department']['department_id']] = $department['Department']['department_name'];

			}


			$departments = json_encode($departmentList);

			return $departments;		
		}
		


	} 




 	/*

 		The computing scientist’s main challenge is not to get confused by the complexities of his own making.

 		        — E. W. Dijkstra
 	*/


 }