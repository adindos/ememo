<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php 

	$this->layout ='mems-404';

	if (Configure::read('debug') > 0):
		echo "<h2>".$message."</h2>";
		echo '<p class="error">';
		echo '<strong>';
		echo __d('cake', 'Error');
		echo '</strong>';
		printf( __d('cake', 'The requested address %s was not found on this server.'),"<strong>'{$url}'</strong>");
		echo '</p>';
		echo $this->element('exception_stack_trace');
	endif;
?>
