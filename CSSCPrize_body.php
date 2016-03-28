<?php
class CSSCPrize extends SpecialPage
{
    function __construct()
    {
	parent::__construct( 'CSSCPrize' ) ;
    }

    function execute( $par ) {
	global $wgRequest, $wgOut;
	
	$this->setHeaders();
	
	# Get request data from, e.g.
	$param = $wgRequest->getText('status');
	if( $param == "submitted" )
	{
	    $this->submitted() ;
	}
	else if( $param == "add" )
	{
	    $this->doadd() ;
	}
	else
	{
	    $this->prizeForm() ;
	}
    }

    function prizeForm() {
	global $wgUser, $wgCedar, $wgRequest, $wgOut;
	global $cgWorksopPrizeDeadline, $cgWorkshopYear ;

	$wgOut->addHTML( "<br>\n" ) ;

	// does this user already have a wiki login?
	$loggedin = $wgUser->isLoggedIn() ;

	$cedarid = 0 ;
	if( !$loggedin )
	{
	    $wgOut->addHTML( "Only Cedar community members may use the Prize Lecture Submission Form. Please log in." ) ;
	    return ;
	}

	$currdate = date_create() ;
	$deadline = date_create( $cgWorksopPrizeDeadline ) ;
	$diff = $deadline->diff( $currdate, FALSE ) ;
	$daysdiff = $diff->invert ;
	if( $daysdiff == 0 )
	{
	    $wgOut->addHTML( "Proposals for the $cgWorkshopYear Prize Lecture Nominations are no longer being accepted" ) ;
	    return ;
	}

	$nominee = trim( $wgRequest->getVal( 'nominee', '' ) );
	$propname = trim( $wgRequest->getVal( 'propname' ) );
	$propemail = trim( $wgRequest->getVal( 'propemail' ) );
	if( !$propemail )
	{
	    if( !$propname )
	    {
		$propemail = $wgUser->getEmail() ;
	    }
	    else
	    {
		$propemail = '' ;
	    }
	}
	if( !$propname )
	{
	    $propname = $wgUser->getRealName() ;
	}
	$citations = trim( $wgRequest->getVal( 'citations', '' ) );
	$comments = trim( $wgRequest->getVal( 'comments', '' ) );

	# Output
	$wgOut->addHTML( "Fill out all fields to nominate a CEDAR prize lecturer, due $cgWorksopPrizeDeadline\n" ) ;
	$wgOut->addHTML( "<br><br>\n" ) ;

	$wgOut->addHTML( "<FORM name=\"cedarprize\" action=\"$wgServer/wiki/index.php/Special:CSSCPrize?status=add\" method=\"POST\">\n" ) ;
	$wgOut->addHTML( "    <TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"660\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"25%\" ALIGN=\"RIGHT\">\n" ) ;
	$wgOut->addHTML( "		<SPAN STYLE=\"font-weight:bold;\">Nominee:&nbsp;&nbsp;</SPAN>\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"75%\" ALIGN=\"LEFT\">\n" ) ;
	$wgOut->addHTML( "		<INPUT TYPE=\"text\" NAME=\"nominee\" value=\"$nominee\" SIZE=\"30\">\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" HEIGHT=\"5\" WIDTH=\"100%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              &nbsp;\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"25%\" ALIGN=\"RIGHT\">\n" ) ;
	$wgOut->addHTML( "		<SPAN STYLE=\"font-weight:bold;\">Proposer:&nbsp;&nbsp;</SPAN>\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"75%\" ALIGN=\"LEFT\">\n" ) ;
	$wgOut->addHTML( "		<INPUT TYPE=\"text\" NAME=\"propname\" value=\"$propname\" SIZE=\"30\">\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" HEIGHT=\"5\" WIDTH=\"100%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              &nbsp;\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"25%\" ALIGN=\"RIGHT\">\n" ) ;
	$wgOut->addHTML( "		<SPAN STYLE=\"font-weight:bold;\">Proposer Email:&nbsp;&nbsp;</SPAN>\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"75%\" ALIGN=\"LEFT\">\n" ) ;
	$wgOut->addHTML( "		<INPUT TYPE=\"text\" NAME=\"propemail\" value=\"$propemail\" SIZE=\"30\">\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" HEIGHT=\"5\" WIDTH=\"100%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              &nbsp;\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" WIDTH=\"100%\" CLASS=\"contexttext\">\n" ) ;
	$wgOut->addHTML( "		<SPAN STYLE=\"font-weight:bold;\">Citations</SPAN> - Add relevant citations from the last 4 years. Can use wiki tags.\n" ) ;
	$wgOut->addHTML( "		<TEXTAREA NAME=\"citations\" ROWS=\"5\" COLS=\"60\">$citations</TEXTAREA>\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" HEIGHT=\"5\" WIDTH=\"100%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              &nbsp;\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" WIDTH=\"100%\" CLASS=\"contexttext\">\n" ) ;
	$wgOut->addHTML( "		<SPAN STYLE=\"font-weight:bold;\">Comments</SPAN> - Why did you choose this nominee? Can use wiki tags. \n" ) ;
	$wgOut->addHTML( "		<TEXTAREA NAME=\"comments\" ROWS=\"5\" COLS=\"60\">$comments</TEXTAREA>\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD COLSPAN=\"2\" HEIGHT=\"5\" WIDTH=\"100%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              &nbsp;\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "	<TR>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"25%\" CLASS=\"contexttext\" ALIGN=\"CENTER\">\n" ) ;
	$wgOut->addHTML( "              <INPUT TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Submit\">\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	    <TD WIDTH=\"75%\" CLASS=\"contexttext\" ALIGN=\"LEFT\">\n" ) ;
	$wgOut->addHTML( "              <INPUT TYPE=\"RESET\" VALUE=\"Reset\">\n" ) ;
	$wgOut->addHTML( "	    </TD>\n" ) ;
	$wgOut->addHTML( "	</TR>\n" ) ;
	$wgOut->addHTML( "    </TABLE>\n" ) ;
	$wgOut->addHTML( "</FORM>\n" ) ;
    }
    
    function submitted() {
	global $wgOut;
	global $cgWorkshopYear ;
	$wgOut->addWikiText( "=Your nomination has been submitted=" ) ; 
	$title = "$cgWorkshopYear" . "_Prize_Lecture_Nominations" ;
	$wgOut->addWikiText( "<BR />Go to the [[$title|Nomination Page]]" ) ;
    }

    function doadd()
    {
	global $wgUser, $wgRequest, $wgOut, $wgAuth, $wgServer ;
	global $cgWorkshopYear, $cgWorksopPrizeRecipientEmail ;
	global $cgWorksopPrizeRecipientName ;

	$nominee = trim( $wgRequest->getVal( 'nominee' ) );
	$propname = trim( $wgRequest->getVal( 'propname' ) );
	$propemail = trim( $wgRequest->getVal( 'propemail' ) );
	$citations = trim( $wgRequest->getVal( 'citations' ) );
	$comments = trim( $wgRequest->getVal( 'comments' ) );

	$found_errors = 0 ;
	if( !$nominee )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red\">You did not specify someone to nominate</SPAN><BR />\n" ) ;
	    $found_errors++ ;
	}
	if( !$propname )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red\">Please include the Proposer's name</SPAN><BR />\n" ) ;
	    $found_errors++ ;
	}
	if( !$propemail )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red\">Please include the Proposer's email address</SPAN><BR />\n" ) ;
	    $found_errors++ ;
	}
	if( !$citations )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red\">You need to specify any citations associated with this nominee</SPAN><BR />\n" ) ;
	    $found_errors++ ;
	}
	if( !$comments )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red\">You need to include comments for this nominee</SPAN><BR />\n" ) ;
	    $found_errors++ ;
	}

	if( $found_errors > 0 )
	{
	    $this->prizeForm() ;
	    return ;
	}

	// add this information to the page
	/* This is not to be done on the cedar site
	$curr_date = date( "d F, Y" ) ;
	$title = "$cgWorkshopYear" . "_Prize_Lecture_Nominations" ;
	$nt = Title::makeTitleSafe( 0, $title ) ;
	if( !$nt )
	{
	    $wgOut->addWikiText( "Could not find the title $title. Please contact
	    [[westp@rpi.edu|Patrick West]] with this information.<br>\n" ) ;
	    return ;
	}
	$article = new Article( $nt, 0 ) ;
	if( !$article )
	{
	    $wgOut->addWikiText( "Could not find the Article $title. Please
	    contact [[westp@rpi.edu|Patrick West]] with this information.<br>\n" ) ;
	    return ;
	}
	$text = $article->getContent() ;
	$text = $text . "\n" ;
	$text = $text . "----" ;
	$text = $text . "\n" ;
	$text = $text . "'''Nominee''': $nominee<br>\n" ;
	$text = $text . "'''Proposer''': $propname<br>\n" ;
	$text = $text . "'''Proposer Email''': $propemail<br>\n" ;
	$text = $text . "'''Date Submitted/Edited''': $curr_date<br>\n" ;
	$text = $text . "'''Citations''':<br>\n$citations<br>\n" ;
	$text = $text . "'''Comments''':<br>\n$comments<br>\n" ;
	$article->doEdit( $text, "Added nomination for $nominee", EDIT_UPDATE ) ;
	*/

	$curr_date = date( "l, F d, Y h:i:s A" ) ;
	$to = new MailAddress( "$cgWorksopPrizeRecipientEmail", "$cgWorksopPrizeRecipientName" ) ;
	$from = new MailAddress( "cedar_db@hao.ucar.edu", "CEDAR Admin" ) ;
	$subject = "[CSSC]: Prize Lecture Nomination from Cedar Community" ;

	$body = "Below is a nomination for the $cgWorkshopYear prize lecture submitted by $propname ($propemail)\n" ;
	$body .= "This nomination was made by a CEDAR user on the CEDAR wiki and must be entered into the CSSC wiki form.\n\n" ;
	$body .= "Nominee: $nominee\n\n" ;
	$body .= "Citations:\n$citations\n\n" ;
	$body .= "Comments:\n$comments\n\n" ;

	$result = UserMailer::send( $to, $from, $subject, $body );

	if( WikiError::isError( $result ) )
	{
	    $wgOut->addHTML( "<SPAN STYLE=\"color:red;\">We were not able to send your nomination.</SPAN>\n" ) ;
	    $wgOut->addHTML( "Please send an email to $cgWorksopPrizeRecipientName at $cgWorksopPrizeRecipientEmail and provide her with the nomination information.\n" ) ;
	    $wgOut->addHTML( "$result\n\n" ) ;
	}

	// redirect to submitted or just call from here
	$this->submitted() ;
    }
}

?>
