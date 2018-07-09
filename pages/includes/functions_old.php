<?php

	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}
	function verify_input($data) {
		global $connection;
  		$data = htmlspecialchars($data);
  		$data = mysqli_real_escape_string($connection,$data);
  		return $data;
    }
    function verify_input_login($data) {
		global $connection;
  		$data = mysqli_real_escape_string($connection,$data);
  		return $data;
    }
	
	function verify_output($data) {

  		$data = stripcslashes($data);
  		$data = htmlspecialchars_decode($data);
  		return $data;
    }
    function verify_output_login($data) {

  		$data = stripcslashes($data);
  		return $data;
    }
	function find_user($userid) {
		global $connection;
		
		$userid = verify_input($userid);
		$query  = "SELECT user_id ";
		$query .= "FROM ptl_users ";
		$query .= "WHERE user_id = '$userid' ";
		$query .= "LIMIT 1";

		$result_set = mysqli_query($connection, $query);
		confirm_query($result_set);
		if($result_user = mysqli_fetch_assoc($result_set)) {
			return $result_user;
		} else {
			return null;
		}
	}


	function find_username($userid) {
		global $connection;
		
		$userid = verify_input($userid);
		$query  = "SELECT first_name ";
		$query .= "FROM ptl_users ";
		$query .= "WHERE u_id = $userid ";
		$result_username = mysqli_query($connection, $query);
		confirm_query($result_username);
		return ($result_username);
	}



	function find_pwd($userid) {
		global $connection;

		$userid = verify_input($userid);
		$query  = "SELECT pass_code ";
		$query .= "FROM ptl_users ";
		$query .= "WHERE user_id = '$userid' ";
		$result_userset = mysqli_query($connection, $query);
		confirm_query($result_userset);
		return mysqli_fetch_assoc($result_userset);
	}


	function find_uid($userid) {
		global $connection;
		
		$userid = verify_input($userid);
		$query  = "SELECT u_id ";
		$query .= "FROM ptl_users ";
		$query .= "WHERE user_id = '$userid' ";
		$query .= "LIMIT 1";
		$result_id = mysqli_query($connection, $query);
		confirm_query($result_id);
		if($result_uid = mysqli_fetch_assoc($result_id)) {
			return $result_uid;
		} else {
			return null;
		}
		
	}

	function find_userdetails($userid) {
		global $connection;
		
		$userid = verify_input($userid);
		$query  = "SELECT user_id, email, first_name, user_image, gravatar, user_role ";
		$query .= "FROM ptl_users ";
		$query .= "WHERE u_id = '$userid' or user_id = '$userid' ";
		$query .= "LIMIT 1";

		$result_id = mysqli_query($connection, $query);
		confirm_query($result_id);
		if($result_uid = mysqli_fetch_assoc($result_id)) {
			return $result_uid;
		} else {
			return null;
		}
		
	}
	function find_alluserdetails() {
		global $connection;
		
		
		$query  = " SELECT 
					ptl_users.U_ID AS U_ID, ptl_users.user_id, ptl_users.first_name, ptl_users.user_image,
					CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE, 
					CASE WHEN TOTAL_Q IS NULL THEN 0 ELSE TOTAL_Q END Q_COUNT 
					FROM ptl_users 
					LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.active = 'TRUE')
					LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' and ptl_questions.active = 'TRUE') 
					LEFT OUTER JOIN  (SELECT SUM(1) TOTAL_Q, ptl_questions.U_ID FROM ptl_questions where ptl_questions.active = 'TRUE' GROUP BY U_ID) T_Q on ptl_users.U_ID = T_Q.U_ID
					GROUP BY ptl_users.U_ID";
		$result_id = mysqli_query($connection, $query);
		confirm_query($result_id);
		
		return $result_id;
		
		
	}
	
	function redirect_to($new_location) {
	  	header("Location: " . $new_location);
	  	exit;
	}

	function insert_question($title,$question,$tag,$uid) {
		global $connection;
		
		$title = verify_input($title);
		//$title = mysqli_real_escape_string($title);
		$question = verify_input($question);
		$tag = verify_input($tag);
		$query  = "INSERT INTO ptl_questions ";
		$query .= "(Q_TITLE, Q_TEXT, Q_TAG, U_ID, CREATION_DATE) ";
		$query .= "VALUES ('$title', '$question', '$tag', $uid, CURDATE()) ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
		if($result_id) {
			$_SESSION["message"] = "Question Posted";
			return true;

		} else {
			$_SESSION["message"] = "Database Error";
			return false;
		}
		
	}
	function insert_user($username,$email,$password,$userpic) {
		global $connection;
		
		$username = verify_input($username);
		$email = verify_input($email);
		$password = verify_input($password);
		if($userpic == ''){
			$userpic = 'default.png';

		}
		$query  = "INSERT INTO ptl_users ";
		$query .= "(USER_ID, EMAIL, PASS_CODE, FIRST_NAME, CREATION_DATE, USER_IMAGE, GRAVATAR) ";
		$query .= "VALUES ('$username', '$email', '$password', '$username', CURDATE(),'$userpic', 'TRUE') ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
		if($result_id) {
			$_SESSION["message"] = "User Created";
			return true;

		} else {
			$_SESSION["message"] = "Database Error";
			return false;
		}
		
	}

	function update_question($title,$question,$tag,$q_id) {
		global $connection;
		
		$title = verify_input($title);
		$question = verify_input($question);
		$tag = verify_input($tag);
		$query  = "UPDATE ptl_questions SET ";
		$query .= "Q_TITLE = '$title', Q_TEXT = '$question', Q_TAG = '$tag' ";
		$query .= "WHERE Q_ID = '$q_id' ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
		// if($result_id) {
		// 	$_SESSION["message"] = "Question Posted";
		// 	return true;

		// } else {
		// 	$_SESSION["message"] = "Database Error";
		// 	return false;
		// }
		
	}

	function update_user($uid,$email,$userpic,$gravatar) {
		global $connection;
		//$q_id = verify_input($q_id);
		$email = verify_input($email);
		$query  = "UPDATE ptl_users SET ";
		$query .= "EMAIL = '$email',USER_IMAGE = '$userpic',GRAVATAR = '$gravatar'  ";
		$query .= "WHERE U_ID = '$uid' ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside update query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		// confirm_query($result_id);
		if($result_id) {
			$_SESSION["message"] = "User details updated";
			return true;

		} else {
			$_SESSION["message"] = "Database Error";
			return false;
		}
	}
	function update_state($q_id,$state) {
		global $connection;
		//$q_id = verify_input($q_id);
		$query  = "UPDATE ptl_questions SET ";
		$query .= "STATE = '$state' ";
		$query .= "WHERE Q_ID = '$q_id' ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside update query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
	}
	function update_active($q_id,$active) {
		global $connection;
		//$q_id = verify_input($q_id);
		$query  = "UPDATE ptl_questions SET ";
		$query .= "ACTIVE = '$active' ";
		$query .= "WHERE Q_ID = '$q_id' ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside update query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
	}


	function insert_answer($answer,$qid,$uid) {
		global $connection;
		$answer = verify_input($answer);
		$qid = verify_input($qid);
		$query  = "INSERT INTO ptl_answers ";
		$query .= "(A_TEXT, Q_ID, U_ID, CREATION_DATE) ";
		$query .= "VALUES ('$answer', '$qid', $uid, CURDATE()) ";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		confirm_query($result_id);
		if($result_id) {
			//$_SESSION["message"] = "Answer Posted";
			return true;

		} else {
			//$_SESSION["message"] = "Database Error";
			return false;
		}
		
	}
	
	function logged_in() {
		return isset($_SESSION['uid']);
	}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		}
	}

	function verify_asker($u_id) {
		//error_log("\nverify_asker" . $u_id . $_SESSION["uid"], 3, "C:/xampp/apache/logs/error.log");
		if (isset($_SESSION['uid']) && $_SESSION["uid"] == $u_id) {
			
			return true;
		}
		else return false;
	}
	function best_answer($a_id,$ba_id,$u_id,$q_id,$state) {

		//error_log("\nbest_answer" . $ba_id . $u_id , 3, "C:/xampp/apache/logs/error.log");
		if (verify_asker($u_id) && ($a_id == $ba_id)) {
			$output  = '<i class="fa fa-check-square-o fa-3x" style = "color: #1d9d74" aria-hidden="true" onclick="best_answer(this)"></i>';
			$output .= '<input type="hidden" name="forid" class="a_id" value = '.$a_id.' />';
			$output .= '<input type="hidden" name="forid" class="u_id" value = '.$ba_id.' />';
			$output .= '<input type="hidden" name="forid" class="q_id" value = '.$q_id.' />';
			$output .= '<input type="hidden" name="forid" class="q_id" value = '.$state.' />';
			return $output;
		}
		if (verify_asker($u_id) && !($a_id == $ba_id) && ($state == "TRUE")) {
			$output ='<i class="fa fa-square-o fa-3x" style = "color: #1d9d74" aria-hidden="true" onclick="best_answer(this)"></i>';
			$output .= '<input type="hidden" name="forid" class="a_id" value = '.$a_id.' />';
			$output .= '<input type="hidden" name="forid" class="u_id" value = '.$ba_id.' />';
			$output .= '<input type="hidden" name="forid" class="q_id" value = '.$q_id.' />';
			$output .= '<input type="hidden" name="forid" class="q_id" value = '.$state.' />';
			return $output;
		}
		if (!verify_asker($u_id) && ($a_id == $ba_id)) {
			return '<i class="fa fa-check-square-o fa-3x" style = "color: #1d9d74" aria-hidden="true"></i>';
		}
}

	function update_answer($a_id,$q_id,$uid) {
		global $connection;
		$q_id = verify_input($q_id);
		$a_id = verify_input($a_id);
		$query  = "UPDATE ptl_questions SET ";
		$query .= "BA_ID = '$a_id' ";
		$query .= "WHERE Q_ID = '$q_id' AND U_ID = '$uid' AND ptl_questions.active = 'TRUE'";
		$result_id = mysqli_query($connection, $query);
		//error_log("Inside update query\n" . $query , 3, "C:/xampp/apache/logs/error.log");
		// confirm_query($result_id);
		if($result_id) {
			//$_SESSION["message"] = "Question Posted";
			return true;

		} else {
			//$_SESSION["message"] = "Database Error";
			return false;
		}
	}
function getimageurl($gravatar,$email,$image){
	if (isset($_SESSION["git_user"]) && $_SESSION["git_user"] == "True"){
		$image_url = $_SESSION["git_image"];
	}
	elseif ($gravatar == "TRUE"){
            $image_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( 'https://s23.postimg.org/aqy6npbob/default.png' );
           } else {
           	$image_url = "userimages/" . $image ;
	}
	return $image_url;
}
	function get_result_1($ques_id,$offset, $rec_limit) {
		                                 	global $connection;

		                                 	$q_id = verify_input($ques_id);


		                                 	$query_1 = "SELECT 
														Q_TITLE, Q_TEXT, Q_TAG, A_TEXT, ptl_answers.A_ID, V_COUNT,
														ptl_users.FIRST_NAME,ptl_users.user_image AS user_image, ptl_users.gravatar AS gravatar, ptl_users.email AS email, ptl_answers.CREATION_DATE, ptl_answers.A_ID, ptl_answers.U_ID,
														CASE WHEN BA_ID = ptl_answers.A_ID THEN 1 ELSE NULL END AS TOP_AID, SCORE
														FROM ptl_answers 
														LEFT OUTER JOIN ptl_questions ON (ptl_answers.Q_ID = ptl_questions.Q_ID  AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_users ON ptl_answers.U_ID = ptl_users.U_ID
														LEFT OUTER JOIN (SELECT  sum(VOTE) AS V_COUNT, A_ID FROM ptl_user_votes  GROUP BY A_ID) v_votes ON ptl_answers.A_ID = v_votes.A_ID
														LEFT OUTER JOIN
														(SELECT ptl_users.U_ID AS U_ID, 
														CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
														FROM ptl_users 
														LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID  AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q'  AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
														ON ptl_users.U_ID = usr_score.U_ID
														WHERE ptl_questions.Q_ID = $q_id ORDER BY TOP_AID desc , V_COUNT desc 
														limit $offset, $rec_limit";


											$result_1 = mysqli_query($connection,$query_1);
											if(!$result_1){die(" get_result_1: Database query failed.");}
											
											return ($result_1);		
	}
	
	function get_result_2($ques_id) {
											global $connection;
											$q_id = verify_input($ques_id);
											$query_2  = "SELECT Q_TITLE, Q_TEXT, Q_TAG, ptl_users.user_image AS user_image,        ptl_questions.CREATION_DATE, ptl_users.gravatar AS gravatar, ptl_users.email AS email,FIRST_NAME,ptl_questions.U_ID,BA_ID, STATE, SCORE
														FROM ptl_questions
														LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID =  ptl_users.U_ID) 
														LEFT OUTER JOIN
														(SELECT ptl_users.U_ID AS U_ID, 
														CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
														FROM ptl_users 
														LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
														ON ptl_users.U_ID = usr_score.U_ID
														WHERE ptl_questions.Q_ID = $q_id ";

                                            $result_2 = mysqli_query($connection,$query_2);
											
                                            if(!$result_2){die("Database query failed.");}
											
											return ($result_2);
		
	}



	function get_questions($user_id,$offset, $rec_limit) {
											global $connection;
											$u_id = verify_input($user_id);
											if(isset($user_id)) 
														{														 
														$query  = "SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS, Q_TITLE, Q_TAG, ptl_questions.Q_ID,
																	ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON, SCORE
																	FROM ptl_questions 
																	LEFT OUTER JOIN ptl_users ON ptl_questions.U_ID=ptl_users.U_ID
																	LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																	LEFT OUTER JOIN
																	(SELECT ptl_users.U_ID AS U_ID, 
																	CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																	FROM ptl_users 
																	LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID  AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q'  AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
																	ON ptl_users.U_ID = usr_score.U_ID
																	WHERE (ptl_questions.U_ID = '$u_id' or ptl_users.user_id = '$u_id') AND ptl_questions.active = 'TRUE' 
																	GROUP BY ptl_questions.Q_ID
																	limit $offset, $rec_limit";	

														}																			
														 
                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.");}
											
											return ($result);
		
	}


	function get_allquestions($user_id,$offset, $rec_limit) {
											global $connection;
											$u_id = verify_input($user_id);
											if(isset($user_id)) 

														{														 
														$query ="SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS,    Q_TITLE, Q_TAG, ptl_questions.Q_ID, ptl_questions.U_ID AS U_ID,  ptl_users.gravatar AS gravatar, ptl_users.email AS email,
																ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON , SCORE
																FROM ptl_questions
																LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID=ptl_users.U_ID  AND ptl_questions.active = 'TRUE')
																LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																LEFT OUTER JOIN
																(SELECT ptl_users.U_ID AS U_ID, 
																CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																FROM ptl_users 
																LEFT OUTER JOIN ptl_questions on ptl_users.U_ID = ptl_questions.U_ID
																LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE' ) GROUP BY ptl_users.U_ID) usr_score
																ON ptl_users.U_ID = usr_score.U_ID
																WHERE ptl_questions.active = 'TRUE'
																GROUP BY ptl_questions.Q_ID 
																limit $offset, $rec_limit";		   
														}																			
														 
                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.".$query);}
											
											return ($result);
}


	function get_all_tag_questions($user_id,$offset, $rec_limit,$q_tag) {
											global $connection;
											$u_id = verify_input($user_id);
											if(isset($user_id)) 

														{														 
														$query ="SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS,    Q_TITLE, Q_TAG, ptl_questions.Q_ID, ptl_questions.U_ID AS U_ID,
																ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON , SCORE,ptl_users.gravatar,ptl_users.email
																FROM ptl_questions
																LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID=ptl_users.U_ID  AND ptl_questions.active = 'TRUE')
																LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																LEFT OUTER JOIN
																(SELECT ptl_users.U_ID AS U_ID, 
																CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																FROM ptl_users 
																LEFT OUTER JOIN ptl_questions on ptl_users.U_ID = ptl_questions.U_ID
																LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE' ) GROUP BY ptl_users.U_ID) usr_score
																ON ptl_users.U_ID = usr_score.U_ID
																WHERE ptl_questions.active = 'TRUE'
																AND (ptl_questions.Q_TAG = '".$q_tag."' 
																  OR ptl_questions.Q_TAG LIKE '% ".$q_tag." %' 
																  OR ptl_questions.Q_TAG LIKE '% ".$q_tag."' 
																  OR ptl_questions.Q_TAG LIKE '".$q_tag." %') 
																GROUP BY ptl_questions.Q_ID 
																limit $offset, $rec_limit";		   
															//	echo $query;
														}																			
														 
                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.".$query);}
											
											return ($result);
		
	}

	function get_allquestions_admin($user_id,$offset, $rec_limit) {
											global $connection;
											$u_id = verify_input($user_id);
											if(isset($user_id)) 
														{														 
														$query  = "
																	SELECT 

																	ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS, Q_TITLE, STATE, ptl_questions.ACTIVE, Q_TAG, ptl_questions.U_ID AS U_ID, 
																	ptl_questions.Q_ID, ptl_users.FIRST_NAME AS FIRST_NAME, ptl_users.user_image AS user_image,
																	ptl_users.gravatar AS gravatar, ptl_users.email AS email, 
																	ptl_questions.CREATION_DATE AS Q_CREATED_ON, SCORE 
																	FROM ptl_questions 
																	LEFT OUTER JOIN ptl_users ON ptl_questions.U_ID=ptl_users.U_ID 
																	LEFT OUTER JOIN ptl_answers ON (ptl_questions.Q_ID = ptl_answers.Q_ID AND ptl_questions.active = 'TRUE')  
																	LEFT OUTER JOIN (SELECT ptl_users.U_ID AS U_ID, CASE WHEN SUM(ptl_user_votes.vote) IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE FROM ptl_users 
																	                                    LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.ACTIVE = 'TRUE') 
																	                                    LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.ACTIVE = 'TRUE') 
																	                                    GROUP BY ptl_users.U_ID) usr_score 
																	ON ptl_users.U_ID = usr_score.U_ID GROUP BY ptl_questions.Q_ID limit $offset, $rec_limit
										           				 ";		   
														}																			
														 
                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.".$query);}
											
											return ($result);
		
	}

	function get_landing_questions($offset, $rec_limit) {
											global $connection;

														$query  =  "SELECT 
																	CASE WHEN A_ID IS NULL THEN 0 ELSE COUNT(*) END AS ANSWERS_COUNT, 
																	VIEWS,ptl_questions.CREATION_DATE AS Q_CREATED_ON, 
																	Q_TITLE, Q_TAG, ptl_questions.Q_ID,ptl_users.FIRST_NAME AS FIRST_NAME,
																	ptl_questions.U_ID AS U_ID,  
																	V_COUNT , ptl_users.user_image AS user_image, SCORE, ptl_users.gravatar, ptl_users.email
																	FROM ptl_questions  
																	LEFT OUTER JOIN ptl_users ON ptl_questions.U_ID=ptl_users.U_ID 
																	LEFT OUTER JOIN ptl_answers ON (ptl_questions.Q_ID = ptl_answers.Q_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN 
																	(SELECT  sum(VOTE) AS V_COUNT, Q_ID FROM ptl_user_votes  GROUP BY Q_ID) q_votes 
																	ON (ptl_questions.Q_ID = q_votes.Q_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN
																	(SELECT ptl_users.U_ID AS U_ID, 
																	CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																	FROM ptl_users 
																	LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
																	ON ptl_users.U_ID = usr_score.U_ID
																	GROUP BY ptl_questions.Q_ID
																	ORDER BY V_COUNT desc limit $offset, $rec_limit";

														 
                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.");}
											
											return ($result);
	}



	function user_votes($id,$v_type) {
											$id      = $id;
										
											$v_type  = $v_type;

											$votes   = get_vote_count($id,$v_type);
											$output  = '<div class="vote roundrect" >';
											$output .= '<input type="hidden" name="forid" value = '.$id.' />';
											
											$output .= '<input type="hidden" name="forid" value = '.$v_type.' />';
											$output .= '<div class="increment up"></div>';
											$output .= '<div class="increment down"></div>';
											$output .= '<div class="count">'.$votes.'</div>';
											$output .= '</div>';

											return $output;
	}											




	function get_vote_count($id,$v_type) {
											global $connection;	
											$votes = 0;
											if ($v_type == 'Q'){
												$query  = "SELECT  sum(VOTE) AS V_COUNT FROM ptl_user_votes WHERE Q_ID = $id AND V_TYPE = '$v_type'       GROUP BY Q_ID";
												$result = mysqli_query($connection,$query);
												if(!$result){die(" que_vote_count: Database query failed.");}
												$votes_array = mysqli_fetch_assoc($result);
											}		
											elseif ($v_type == 'A') {
											
												$query  = "SELECT  sum(VOTE) AS V_COUNT FROM ptl_user_votes WHERE A_ID = $id AND V_TYPE = '$v_type'       GROUP BY A_ID";										
												$result = mysqli_query($connection,$query);
												if(!$result){die(" ans_vote_count: Database query failed.");}
												$votes_array = mysqli_fetch_assoc($result);														
											}										
											else{};
																						
							                if ($votes_array["V_COUNT"] != NULL) 
							                   {$votes = $votes_array["V_COUNT"];} 
							                else {$votes = 0;};
											
											return ($votes);
	}






	function vote_ins_upd($id,$vote,$v_type) {

											global $connection;
											$u_id = $_SESSION['uid'];
											$id = verify_input($id);
											$u_id = verify_input($u_id);
											$vote = verify_input($vote);
											$v_type = verify_input($v_type);
											//echo $u_id,$q_id;

											if ( $v_type == 'Q' ){
												$query_1  = "SELECT 1 AS rec_exists, VOTE FROM ptl_user_votes WHERE Q_ID = $id and U_ID = $u_id and V_TYPE = 'Q'";
												//echo $query_1;
												$result_1 = mysqli_query($connection,$query_1);

												if(!$result_1){die("vote_ins_upd-query_1: Database query failed.");}

												$records_1 = mysqli_fetch_assoc($result_1);

												$new_vote = $records_1["VOTE"]+($vote);
												$rec_exists = $records_1["rec_exists"];

												//echo "rec_exists: ". $rec_exists ;


												if(($new_vote == (-1) or $new_vote == 0 or $new_vote == 1) and $rec_exists == 1){
																				$query  = "UPDATE ptl_user_votes SET VOTE = $new_vote,                  LAST_UPDATION_DATE = CURDATE() WHERE Q_ID = $id AND U_ID = $u_id AND V_TYPE='Q' ";	
																				//echo $query;																			

																				$result_id = mysqli_query($connection, $query);
																				if(!$result_1){die("vote_ins_upd-Q-UPDATE: Database query failed.");}																				
																				confirm_query($result_id);     
											    }
												elseif(($new_vote == (-1) or $new_vote == 0 or $new_vote == 1) and $rec_exists != 1){
																				$query  = "INSERT INTO ptl_user_votes ";
																				$query .= "(Q_ID, U_ID, VOTE, V_TYPE, CREATION_DATE) ";
																				$query .= "VALUES ('$id', '$u_id', $vote, 'Q', CURDATE()) ";
																				//echo $query;											

																				$result_id = mysqli_query($connection, $query);
																				confirm_query($result_id);  			
												}
												else {};
											}

											elseif ($v_type == 'A'){
												$query_1  = "SELECT 1 AS rec_exists, VOTE FROM ptl_user_votes WHERE A_ID = $id and U_ID = $u_id and V_TYPE = 'A'";
												//echo $query_1;
												$result_1 = mysqli_query($connection,$query_1);

												if(!$result_1){die("Answer vote_ins_upd-query_1: Database query failed.");}

												$records_1 = mysqli_fetch_assoc($result_1);

												$new_vote = $records_1["VOTE"]+($vote);
												$rec_exists = $records_1["rec_exists"];

												//echo "rec_exists: ". $rec_exists ;


												if(($new_vote == (-1) or $new_vote == 0 or $new_vote == 1) and $rec_exists == 1){
																				$query  = "UPDATE ptl_user_votes SET VOTE = $new_vote,                  LAST_UPDATION_DATE = CURDATE() WHERE A_ID = $id AND U_ID = $u_id AND V_TYPE='A' ";	
																				//echo $query;																			

																				$result_id = mysqli_query($connection, $query);
																				if(!$result_1){die("vote_ins_upd-A-UPDATE: Database query failed.");}																				
																				confirm_query($result_id);     
											    }
												elseif(($new_vote == (-1) or $new_vote == 0 or $new_vote == 1) and $rec_exists != 1){
																				$query  = "INSERT INTO ptl_user_votes ";
																				$query .= "(A_ID, U_ID, VOTE, V_TYPE, CREATION_DATE) ";
																				$query .= "VALUES ('$id', '$u_id', $vote, 'A', CURDATE()) ";
																				//echo $query;											

																				$result_id = mysqli_query($connection, $query);
																				confirm_query($result_id);  			
												}
												else {};

											}

											else {};

											return true;
	}



	function get_query($query_name,$p1_name,$p1_value) {

			if ($query_name == 'landing_page_questions'){

														$query  =  "SELECT COUNT(*) Q_CNT FROM (SELECT 
																	CASE WHEN A_ID IS NULL THEN 0 ELSE COUNT(*) END AS ANSWERS_COUNT, 
																	VIEWS,ptl_questions.CREATION_DATE AS Q_CREATED_ON, 
																	Q_TITLE, Q_TAG, ptl_questions.Q_ID,ptl_users.FIRST_NAME AS FIRST_NAME, 
																	V_COUNT , ptl_users.user_image AS user_image, SCORE
																	FROM ptl_questions  
																	LEFT OUTER JOIN ptl_users ON ptl_questions.U_ID=ptl_users.U_ID 
																	LEFT OUTER JOIN ptl_answers ON (ptl_questions.Q_ID = ptl_answers.Q_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN 
																	(SELECT  sum(VOTE) AS V_COUNT, Q_ID FROM ptl_user_votes  GROUP BY Q_ID) q_votes 
																	ON (ptl_questions.Q_ID = q_votes.Q_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN
																	(SELECT ptl_users.U_ID AS U_ID, 
																	CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																	FROM ptl_users 
																	LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
																	ON ptl_users.U_ID = usr_score.U_ID
																	GROUP BY ptl_questions.Q_ID) CNT";
														}

			elseif ($query_name == 'get_allquestions') {
														$query  = "SELECT COUNT(*) Q_CNT FROM (";
														$query .= "SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS,    Q_TITLE, Q_TAG, ptl_questions.Q_ID,
																ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON , SCORE
																FROM ptl_questions
																LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID=ptl_users.U_ID  AND ptl_questions.active = 'TRUE')
																LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																LEFT OUTER JOIN
																(SELECT ptl_users.U_ID AS U_ID, 
																CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																FROM ptl_users 
																LEFT OUTER JOIN ptl_questions on ptl_users.U_ID = ptl_questions.U_ID
																LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE' ) GROUP BY ptl_users.U_ID) usr_score
																ON ptl_users.U_ID = usr_score.U_ID
																WHERE ptl_questions.active = 'TRUE'
																GROUP BY ptl_questions.Q_ID) CNT";																		
																				}	
			elseif ($query_name == 'get_questions')	{   
														$query  = "SELECT COUNT(*) Q_CNT FROM (";
														$query .= "SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS, Q_TITLE, Q_TAG, ptl_questions.Q_ID,
																	ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON, SCORE
																	FROM ptl_questions 
																	LEFT OUTER JOIN ptl_users ON ptl_questions.U_ID=ptl_users.U_ID
																	LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																	LEFT OUTER JOIN
																	(SELECT ptl_users.U_ID AS U_ID, 
																	CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																	FROM ptl_users 
																	LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID  AND ptl_questions.active = 'TRUE')
																	LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q'  AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
																	ON ptl_users.U_ID = usr_score.U_ID
																	WHERE (ptl_questions.U_ID = '".$p1_value."' or ptl_users.user_id = '".$p1_value."') AND ptl_questions.active = 'TRUE'
																	GROUP BY ptl_questions.Q_ID) CNT";	

													}

			elseif ($query_name == 'get_result_1') {
														$query =   "SELECT COUNT(*) Q_CNT FROM (  SELECT 
														Q_TITLE, Q_TEXT, Q_TAG, A_TEXT, ptl_answers.A_ID, V_COUNT, ptl_users.FIRST_NAME,
														ptl_users.user_image AS user_image, ptl_answers.CREATION_DATE, ptl_answers.U_ID,
														CASE WHEN BA_ID = ptl_answers.A_ID THEN 1 ELSE NULL END AS TOP_AID, SCORE
														FROM ptl_answers 
														LEFT OUTER JOIN ptl_questions ON (ptl_answers.Q_ID = ptl_questions.Q_ID  AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_users ON ptl_answers.U_ID = ptl_users.U_ID
														LEFT OUTER JOIN (SELECT  sum(VOTE) AS V_COUNT, A_ID FROM ptl_user_votes  GROUP BY A_ID) v_votes ON ptl_answers.A_ID = v_votes.A_ID
														LEFT OUTER JOIN
														(SELECT ptl_users.U_ID AS U_ID, 
														CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
														FROM ptl_users 
														LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID  AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q'  AND ptl_questions.active = 'TRUE') GROUP BY ptl_users.U_ID) usr_score
														ON ptl_users.U_ID = usr_score.U_ID
														WHERE ptl_questions.Q_ID = ".$p1_value.") CNT";
			}																															

			elseif ($query_name == 'get_allquestions_admin') {
														$query  = "SELECT COUNT(*) Q_CNT FROM (";
														$query .= "SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS,    Q_TITLE, Q_TAG, ptl_questions.Q_ID,
																ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON , SCORE
																FROM ptl_questions
																LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID=ptl_users.U_ID  AND ptl_questions.active = 'TRUE')
																LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																LEFT OUTER JOIN
																(SELECT ptl_users.U_ID AS U_ID, 
																CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																FROM ptl_users 
																LEFT OUTER JOIN ptl_questions on ptl_users.U_ID = ptl_questions.U_ID
																LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE' ) GROUP BY ptl_users.U_ID) usr_score
																ON ptl_users.U_ID = usr_score.U_ID
																GROUP BY ptl_questions.Q_ID) CNT";
															}	
			elseif ($query_name == 'questionsbytag') {
														$query  = "SELECT COUNT(*) Q_CNT FROM (";
														$query .="SELECT ptl_questions.UP_VOTE, COUNT(*) AS ANSWERS_COUNT, VIEWS,    Q_TITLE, Q_TAG, ptl_questions.Q_ID, ptl_questions.U_ID AS U_ID,
																ptl_users.FIRST_NAME AS FIRST_NAME,ptl_users.user_image AS user_image,ptl_questions.CREATION_DATE AS Q_CREATED_ON , SCORE
																FROM ptl_questions
																LEFT OUTER JOIN ptl_users ON (ptl_questions.U_ID=ptl_users.U_ID  AND ptl_questions.active = 'TRUE')
																LEFT OUTER JOIN ptl_answers ON ptl_questions.Q_ID = ptl_answers.Q_ID 
																LEFT OUTER JOIN
																(SELECT ptl_users.U_ID AS U_ID, 
																CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
																FROM ptl_users 
																LEFT OUTER JOIN ptl_questions on ptl_users.U_ID = ptl_questions.U_ID
																LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE' ) GROUP BY ptl_users.U_ID) usr_score
																ON ptl_users.U_ID = usr_score.U_ID
																WHERE ptl_questions.active = 'TRUE'
																AND (ptl_questions.Q_TAG = '".$p1_name."' 
																  OR ptl_questions.Q_TAG LIKE '% ".$p1_name." %' 
																  OR ptl_questions.Q_TAG LIKE '% ".$p1_name."' 
																  OR ptl_questions.Q_TAG LIKE '".$p1_name." %') 
																GROUP BY ptl_questions.Q_ID) Q"; 
																	

															
													 }																
			else {}
			

			return ($query);
	}

	function get_row_count($query_name,$p1_name,$p1_value) {
											global $connection;
											$query = get_query($query_name,$p1_name,$p1_value);
                                            $result = mysqli_query($connection,$query);										
                                            if(!$result){die("get_row_count: Database query failed.");}
                                            $row = mysqli_fetch_assoc($result);											
											$cnt = $row["Q_CNT"];	
											return ($cnt);
	}


	function generate_pagination_buttons($rec_limit,$cnt,$page,$page_name,$p1_name,$p1_value) {
         
         $Buttons = ceil($cnt / $rec_limit);

         $output  = "<div class='text-center'>";
         $output .=  "<ul class='pagination'>";

         if ($page == '') 
         		{$output .= "<li><a class='active' href='".$page_name."?".$p1_name."=".$p1_value."'>Home</a></li>";}
         else   
         		{$output .= "<li><a href='".$page_name."?".$p1_name."=".$p1_value."''>Home</a></li>";}
         
         for($i=1;$i<=($Buttons-1);$i++){
            if ($page==$i)
            	{$output .= "<li><a class='active' href='".$page_name."?page=".$i."&".$p1_name."=".$p1_value."'>".$i."</a></li>";}
            else 
            	{$output .= "<li><a href='".$page_name."?page=".$i."&".$p1_name."=".$p1_value."'>".$i."</a></li>";}            
         }

         $output .= "</ul>";
         $output .= "</div>";

         return ($output);

	}


	function get_user_score($u_id) {
											global $connection;
											$u_id = verify_input($u_id);
											$query  =  "SELECT ptl_users.U_ID AS U_ID, 
														CASE WHEN SUM(ptl_user_votes.vote)  IS NULL THEN 0 ELSE SUM(ptl_user_votes.vote) END AS SCORE 
														FROM ptl_users 
														LEFT OUTER JOIN ptl_questions on (ptl_users.U_ID = ptl_questions.U_ID AND ptl_questions.active = 'TRUE')
														LEFT OUTER JOIN ptl_user_votes on (ptl_questions.Q_ID = ptl_user_votes.Q_ID and ptl_user_votes.V_TYPE = 'Q' AND ptl_questions.active = 'TRUE')
														WHERE ptl_users.USER_ID = '$u_id' or ptl_users.U_ID = '$u_id' GROUP BY ptl_users.U_ID";

                                            $result = mysqli_query($connection,$query);
											
                                            if(!$result){die("Database query failed.");}
											
											return ($result);}


	function git_validate_user($user_name) {
		                                 	global $connection;
		                                 	$user_name = verify_input($user_name);
		                                 	$query = "SELECT u_id,user_id from ptl_users where user_id = '$user_name'";
											$result = mysqli_query($connection,$query);
											if(!$result){die(" git_validate_user: Database query failed.");}
											
											return ($result);	
										}


?>


