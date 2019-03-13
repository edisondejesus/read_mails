<?php




	class  Gmail_api 
	{
		
			
	 function read_messages($token){


			$api="https://www.googleapis.com/gmail/v1/users/me/messages?oauth_token=$token&maxResults=30";

			$id_messages=[];
			$resp  =file_get_contents($api);
			$resp =  json_decode($resp);

			$count_mess =  count($resp->messages);

		

			for($i=0;$i<$count_mess;$i++){

				$id_messages[] =  $resp->messages;

			}

			for($i=0;$i<count($id_messages[0]);$i++){

			$id = $id_messages[0][$i]->id;

			#return json_encode($id_messages);
			$get_messages = "https://www.googleapis.com/gmail/v1/users/me/messages/id?oauth_token=$token&id=$id";
			$response = file_get_contents($get_messages);
			$response  = json_decode($response);
		
			$cantidad_header = count($response->payload->headers);
			for($u=0;$u<$cantidad_header;$u++){

				if($response->payload->headers[$u]->name=="From"){

					if(isset($response->payload->parts[0]->body->data)){

						$messages_ready[] = array(
						'from'=>$response->payload->headers[$u]->value,
						'body'=>base64_decode(strtr($response->payload->parts[0]->body->data,'-_', '+/')),			


						);
				}
			}


			}

		if(isset($messages_ready)){
			foreach ($messages_ready as $key) {
		
				
				$messages_ready_ok[]= $key;	

			}
			
		}

		}
			echo json_encode($messages_ready);


		}


		function send_messages($token){
			#Set token in the param for the api send email from  the user de this token

			#With this functione you send message to a destine

		$user = 'me';
		$strSubject = 'Test mail using GMail API' . date('M d, Y h:i:s A');
		$strRawMessage = "From: myAddress<myemail@gmail.com>\r\n";
		$strRawMessage .= "To: toAddress <recptAddress@gmail.com>\r\n";
		$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
		$strRawMessage .= "MIME-Version: 1.0\r\n";
		$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
		$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
		$strRawMessage .= "this <b>is a test message!\r\n";
		// The message needs to be encoded in Base64URL
		$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
	

			#send message for gmail
			$api="https://www.googleapis.com/gmail/v1/users/me/messages/send";
			$ch = curl_init($api);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,array(
						'oauth_token'=>$token,
						'raw'=>$mime
					 ));
			$resp=curl_exec($ch);






		}







	}












?>