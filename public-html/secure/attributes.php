<!DOCTYPE html>
<html lang="en">
<head>
<title>OIDC Reflector</title>
<link rel="stylesheet" href="../public/styles.css">
</head>
<body>

<h1>Attribute Reflector - OIDC</h1>
<a href="../public/attributes.php"><b>Access the unprotected page</b></a> <br><br>
<a href="/secure/redirect_uri?logout=https%3A%2F%2Fclients.bio.ausfed.net">Logout</a>

<a href="/secure/redirect_uri?logout=https://clients.bio.ausfed.net/">Logout</a>


<table> <tr> <th>Attributes</th> <th>Values</th> </tr>

<!-- error_reporting(E_ALL & ~E_NOTICE);  -->
<?php error_reporting(0);

$list = [
  'REMOTE_USER',
  'OIDC_CLAIM_affiliation',
  'OIDC_CLAIM_email',
  'OIDC_CLAIM_eppn',
  'OIDC_CLAIM_eptid',
  'OIDC_CLAIM_family_name',
  'OIDC_CLAIM_given_name',
  'OIDC_CLAIM_name',
  'OIDC_CLAIM_idp',
  'OIDC_CLAIM_idp_name',
  'OIDC_CLAIM_isMemberOf',
  'OIDC_access_token',
  'OIDC_CLAIM_sub',
  'OIDC_CLAIM_iss',
  'OIDC_CLAIM_jti',
  'OIDC_CLAIM_nonce',
  'OIDC_CLAIM_auth_time',
  'OIDC_CLAIM_exp',
  'OIDC_access_token_expires',
  'HTTPS',
  'OIDC_CLAIM_acr',
  'OIDC_CLAIM_aud',
  'OIDC_CLAIM_terms_and_conditions',
];

function isMemberOf_list ($val) {
  array_walk($val, function(&$item, $idx) {
    print $item . "<br>";
  } );
}
// Prints the above claims with their values.

function token_explode($val, $type) {
  print ("<table> <tr> <th>$type</th> <th>Values</th> </tr>");
  foreach ($val as $idx => $item) {
  //array_walk($val, function(&$item ,$idx) {
    if ( is_array($item) ) {
      print ("<tr><td>" . $idx . "</td><td>");
      token_explode($item, $idx);
      print ("</td></tr>");
    } else {
      print ("<tr><td>" . $idx . "</td><td>" . $item . "</td></tr>");
    }
  } // );
  print ("</table>");
}

foreach ($list as $claim) {
  print ("<tr><td>" . $claim . "</td><td>");
  if ( is_null($_SERVER[$claim]) ) {
    print ("<div class='novalue'>no value</div>");

  } else {
    if ( $claim == "OIDC_CLAIM_isMemberOf" )  {
      $isMemberOf_split = preg_split('/(\,)/', $_SERVER['OIDC_CLAIM_isMemberOf'], -1,);
      token_explode($isMemberOf_split, "GROUPS");
//      isMemberOf_list ($isMemberOf_split);

    } elseif ( $claim == "OIDC_access_token" ) {
        print ("<div class='token'>TOKEN</div>" );
        print ($_SERVER['OIDC_access_token'] . "<br>"); 

        if ( ! is_null($_SERVER['OIDC_access_token']) ) {
          $token1=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER[$claim])[1]))));
          $token0=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER[$claim])[0]))));

          print ("<br>");
          print ("<div class='token'>DECODED TOKEN</strong> </div>");

	  if ( ! is_null($token0) ) {
	    print ("<b>[Token Header]</b>");
            token_explode($token0, "Header Artefacts");
          } else {
            print ("NOT a decodeable Access Token");
          }

          if ( ! is_null($token1) ) {
            print ("<br>");
	    print ("<b>Token Data</b>");
	    token_explode($token1, "Data Artefacts");
          } else {
            print ("<br>" . "NOT a JWT....");
          }
	}

    } elseif ( $claim == "OIDC_CLAIM_terms_and_conditions" ) {
        print ("<div class='token'>Terms and Conditions List</div>" );
	
	print ($_SERVER['OIDC_CLAIM_terms_and_conditions']);
	if ( isset($_SERVER[$claim]) ) {
	  print ( $_SERVER[$claim][0] . "<br>");
	  print ( var_dump($_SERVER[$claim][0]) );
	  print ( "<br>>-----<<br>" );
	  $t1 = explode('.', $_SERVER[$claim]);
//	  $token2 = base64_decode($t1 ,true);
//	  print ("token2: " . $token2);
	}

        if ( ! is_null($_SERVER[$claim]) ) {
          $token1=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER[$claim])[1]))));
          $token0=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER[$claim])[0]))));

          print ("<br>");
          print ("<div class='token'>T&C Claim TOKEN</strong> </div>");

          if ( ! is_null($token0) ) {
            print ("<b>[Token Header]</b>");
            token_explode($token0, "Header Artefacts");
          } else {
            print ("NOT a decodeable Access Token");
          }

          if ( ! is_null($token1) ) {
            print ("<br>");
            print ("<b>Token Data</b>");
            token_explode($token1, "Data Artefacts");
          } else {
            print ("<br>" . "NOT a JWT....");
          }
        }

    } else {
      print ($_SERVER[$claim]);
    }
  }
  print ("</td></tr>");
}
?>
</tbody> </table>
<div class="makeleft">
<!-- Prints all info   (INFO_VARIABLES)  -->
<?php
 phpinfo(INFO_VARIABLES);
?>
</div>
<!-- -->
</body>
</html>

