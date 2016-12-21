<?php

class MyClass
{
  public $prop1 = "I'm a class property!";
 
  public function setProperty($newval)
  {
      $this->prop1 = $newval;
  }
 
  public function getProperty()
  {
      return $this->prop1 . "<br />";
  }
}


class CustomerCode
{
  public $prop1 = "I'm a class property!";
 
  public function setProperty($newval)
  {
      $this->prop1 = $newval;
  }
 
  public function getProperty()
  {
      return $this->prop1 . "<br />";
  }
}


class CustomerCode
{
	public $CustomerCode;
	public $NumOfCompanies;
	public $SqlWhereString;
	public $SqlWhereAttribute;
	public $CustomerCodeArray;
	
	function __consturct() {
        // stuff
    }
    function __destroy() {
        // un-stuff and flag setting
    }
	
	public function $getCustomerCode()
		CustomerCode = $CustomerCode
	end property
	public function let CustomerCode(p_CustomerCode)
		m_CustomerCode = p_CustomerCode
	end property

	public function $getNumOfCompanies()
		if instr(m_CustomerCode, ",") > 0 then
			dim tmpArray
			tmpArray = Split(m_CustomerCode, ",")
			NumOfCompanies = ubound(tmpArray) + 1
			set tmpArray = nothing
		elseif len(m_CustomerCode) > 0 then
			NumOfCompanies = 1
		else
			NumOfCompanies = 0
		end if
	end property

	public function $getSqlWhereString()
		'SqlWhereAttribute format: HistoryDetail.Customer
		dim p_CustomerCodes
		dim p_CustomerCode
		dim p_Sql : p_Sql = ""
		
		p_CustomerCodes = Split(m_CustomerCode, ",")
		for each p_CustomerCode in p_CustomerCodes
			Sql = Sql & $SqlWhereAttribute & "='" & trim(p_CustomerCode) & "' OR "
		next
		if len(Sql) > 0 then
			SqlWhereString = "(" & left(Sql, len(Sql) - 4) & ")"
		end if
	end property

	public function $getSqlWhereAttribute()
		SqlWhereAttribute = $SqlWhereAttribute
	end property
	public function let SqlWhereAttribute(p_SqlWhereAttribute)
		m_SqlWhereAttribute = p_SqlWhereAttribute
	end property

	public function $getCustomerCodeArray()
		CustomerCodeArray = Split(m_CustomerCode, ",")
		print "m_CustomerCode: " & $CustomerCode
		for each customer in CustomerCodeArray
			print customer
		next
		
	end property

end class

function objCompanySelector(thisSelected, showInternal, strOnChange)
	dim thisSelectedCopy : thisSelectedCopy = thisSelected
	dim strBoiSelected   : strBoiSelected   = ""
	dim strPerSelected   : strPerSelected   = ""
	dim strBurSelected   : strBurSelected   = ""
	dim strPPiSelected   : strPPiSelected   = ""
	dim strPocSelected   : strPocSelected   = ""
	dim strIdaSelected   : strIdaSelected   = ""
	dim strTwiSelected   : strTwiSelected   = ""
	dim strBseSelected   : strBseSelected   = ""
	dim strBlySelected   : strBlySelected   = ""
    dim strOreSelected   : strOreSelected   = ""
    dim strWyoSelected   : strWyoSelected   = ""
	
	if isnumeric(thisSelected) then
		thisSelectedCopy = getTempsCompCode(thisSelected)
	else
		thisSelectedCopy = thisSelected
	end if
	
	select case ucase(thisSelectedCopy)
	case "BUR"
		strBurSelected = " selected"
	case "BOI"
		strBoiSelected = " selected"
	case "PER"
		strPerSelected = " selected"
	case "IDA"
		strIdaSelected = " selected"
	case "POC"
		strPocSelected = " selected"
	case "PPI"
		strPPiSelected = " selected"
	case "TWI"
		strTwiSelected = " selected"
	case "BSE"
		strBseSelected = " selected"
	case "BLY"
		strBlySelected = " selected"
    case "ORE"
		strOreSelected = " selected"
    case "WYO"
		strWyoSelected = " selected"
	end select

		
	dim strResponseBuffer
	strResponseBuffer = "" &_
			"<label for=""whichCompany"">Select Location</label>" &_
			"<select name=""whichCompany"" id=""whichCompany"" class=""styled"" onchange=""" & strOnChange & """>" &_
				"<option value="""">--- Select Area ---</option>" &_
				"<option value=""POC""" & strPocSelected & ">Pocatello</option>" &_
				"<option value=""BUR""" & strBurSelected & ">Burley</option>" &_
				"<option value=""PER""" & strPerSelected & ">Twin Falls and Jerome</option>" &_
				"<option value=""BOI""" & strBoiSelected & ">Boise and Nampa</option>" &_
				"<option value=""IDA""" & strIdaSelected & ">Idaho Department of Ag</option>" &_
				"<option value=""PPI""" & strPPiSelected & ">PPI</option>" &_
                "<option value=""ORE""" & strOreSelected & ">Oregon</option>" &_
                "<option value=""WYO""" & strWyoSelected & ">Wyoming</option>"
				
				if showInternal then
					strResponseBuffer = strResponseBuffer &_
					
						"<option value=""TWI""" & strTwiSelected & ">Twin Internal</option>" &_
						"<option value=""BSE""" & strBseSelected & ">Boise Internal</option>" &_
						"<option value=""BLY""" & strBlySelected & ">Burley Internal</option>"
				end if
	strResponseBuffer = strResponseBuffer &_
			"</select>"

	objCompanySelector = strResponseBuffer
end function

'---------------------------------------------------------------------------------------------------
function getSiteNumber(thisCode)
	getSiteNumber = getTempsDSN(thisCode)
end function

function getTempsDSNbyCode(thisCode)
	getTempsDSNbyCode = getTempsDSN(thisCode)
end function

function getTempsDSN(thisCode)
	select case trim(lcase(thisCode))
	case "ida"
		getTempsDSN = IDA
	case "per"
		getTempsDSN = PER
	case "bur"
		getTempsDSN = BUR
	case "boi"
		getTempsDSN = BOI
	case "ppi"
		getTempsDSN = PPI
	case "bse"
		getTempsDSN = BSE
	case "twi"
		getTempsDSN = TWI
	case "bly"
		getTempsDSN = BLY
	case "poc"
		getTempsDSN = POC
    case "ore"
		getTempsDSN = ORE
	case "wyo"
		getTempsDSN = WYO
	case "na"
		getTempsDSN = null
	case else
		getTempsDSN = -1
		'print "User Site, Company or VMS2Temps User mapping has not been set in profile"
	end select
end function

public function getCompanyNumber (thisCode)
	getCompanyNumber = getTempsDSN(thisCode)
end function

public function getTempsCompCode(thisDSN)
	if isnumeric(thisDSN) then
		select case cint(thisDSN)
		case IDA
			getTempsCompCode = "IDA"
		case PER
			getTempsCompCode = "PER"
		case POC
			getTempsCompCode = "POC"
		case BUR
			getTempsCompCode = "BUR"
		case BOI
			getTempsCompCode = "BOI"
		case PPI
			getTempsCompCode = "PPI"
        case ORE
			getTempsCompCode = "ORE"
		case WYO
			getTempsCompCode = "WYO"
		case else
			getTempsCompCode = null
		end select
	else
		select case cint(getTempsDSN(thisDSN))
		case IDA
			getTempsCompCode = "IDA"
		case PER
			getTempsCompCode = "PER"
		case POC
			getTempsCompCode = "POC"
		case BUR
			getTempsCompCode = "BUR"
		case BOI
			getTempsCompCode = "BOI"
		case PPI
			getTempsCompCode = "PPI"
        case ORE
			getTempsCompCode = "ORE"
		case WYO
			getTempsCompCode = "WYO"
		case else
			getTempsCompCode = null
		end select
	end if
end function

public function getTempsSiteId(thisDSN)
	if isnumeric(thisDSN) then
		getTempsSiteId = thisDSN
	else
		getTempsSiteId = getTempsDSN(thisDSN)
	end if
end function

public function getCompCode (iCode)
	getCompCode = getTempsCompCode(iCode)
end function

public function output_debug (thisData)
	debug_log.WriteLine(thisData & "")
end function
	
public function ClearHTMLTags(strHTML, intWorkFlow)
  'Variables used in the function
		
  dim regEx, strTagLess
		
  '---------------------------------------
  strTagless = strHTML
  'Move the string into a private variable
  'within the function
  '---------------------------------------

  'regEx initialization
  '---------------------------------------
  set regEx = New RegExp 
  'Creates a regexp object		
  regEx.IgnoreCase = True
  'Don't give frat about case sensitivity
  regEx.Global = True
  'Global applicability
  '---------------------------------------

  'Phase I
  '	"bye bye html tags"
  if intWorkFlow <> 1 then
    '---------------------------------------
    regEx.Pattern = "<[^>]*>"
    'this pattern mathces any html tag
    strTagLess = regEx.Replace(strTagLess, "")
    'all html tags are stripped
    '---------------------------------------
  end if
		
  'Phase II
  '	"bye bye rouge leftovers"
  '	"or, I want to render the source"
  '	"as html."

  '---------------------------------------
  'We *might* still have rouge < and > 
  'let's be positive that those that remain
  'are changed into html characters
  '---------------------------------------	

  if intWorkFlow > 0 and intWorkFlow < 3 then
    regEx.Pattern = "[<]"
    'matches a single <
    strTagLess = regEx.Replace(strTagLess, "<")

    regEx.Pattern = "[>]"
    'matches a single >
    strTagLess = regEx.Replace(strTagLess, ">")
    '---------------------------------------
  end if
		
  'Clean up
  '---------------------------------------
  set regEx = nothing
  'Destroys the regExp object
  '---------------------------------------	
		
  '---------------------------------------
  ClearHTMLTags = strTagLess
  'The results are passed back
  '---------------------------------------
end function

'---------------------------------------------------------------------------------------------------
public function decorateTop (itemid, itemclass, itemheading)
	'place holder function for compatibility
	response.write createtop (itemid, itemclass, itemheading)
end function

function createtop (itemid, itemclass, itemheading)
	dim idcontent, classcontent, headingcontent, nospace, bugReport
	
	if len(itemid) >0 then idcontent = "id=" & chr(34) & itemid & chr(34)
	classcontent = "class=""" & Trim(itemclass & " ") & chr(34) 
	bugReport = "<span class='bug'><a href=""/include/system/tools/bug_report/"" title=""Report A Bug""  onclick=""grayOut(true);"">" &_
		"<img src='" & imageURL & "/include/style/images/mnuBugReport.png' alt='Bug Report'></a></span>"
	if len(itemheading) >0 then
		if instr(itemheading, "<h") > 0 and instr(itemheading, "</h") > 0 then
			headingcontent = replace(itemheading, "</h", bugReport & "</h")
		else
			headingcontent = "<h4>" & itemheading & bugReport & "</h4>"
		end if
	end if
	classcontent = Trim(idcontent & " " & classcontent)
	createtop = "<div " & classcontent & "><div class=""tb""><div><div></div></div></div><div class=""mb clearfix"">" & headingcontent

end function

'---------------------------------------------------------------------------------------------------
function decorateBottom ()
	response.write closeit()
end function

function closeit()
	closeit = "</div><div class='bb'><div><div>&nbsp;</div></div></div></div>"
end function

'---------------------------------------------------------------------------------------------------
function no_cache
	Response.CacheControl = "no-cache" 
	Response.AddHeader "Pragma","no-cache"
	Response.Expires = -1
end function

'---------------------------------------------------------------------------------------------------
REM function decorateBottom ()
	REM response.write("</div><div class='bb'><div><div>&nbsp;</div></div></div></div>")
REM end function

'---------------------------------------------------------------------------------------------------
function fetchResource (ID)
		Database.Open MySql
		set resource = Database.Execute("SELECT * FROM tbl_resources WHERE ID=" & ID)
		if Not resource.eof  then
			if user_level => userLevelPPlusSupervisor then
				manageResource = "<a class='editResource' href=""/include/system/editSiteResource.asp?" &_
				"resourceID=" & ID & """ title='Edit Resource Content'><img src='" & imageURL & "/include/style/images/blogEdit.png' alt='' ></a>"
			end if
			ID = resource("ID")
			classID = resource("classID")
			heading = resource("heading") & manageResource
			content = resource("content")
			Response.write decorateTop(ID, classID, heading) & content & decorateBottom()
		end if
		set Resource = nothing
		Database.Close
end function

'---------------------------------------------------------------------------------------------------
function PopulateList (listSource, listValue, listDisplay, sortBy, thisOneSelected)
	dim dbQuery, SelectedItem, dbItem
	SystemDatabase.Open MySql
	'break "Select " & listValue & ", " & listDisplay & " From " & listSource & " " & sortBy
	set dbQuery = SystemDatabase.Execute("Select " & listValue & ", " & listDisplay & " From " & listSource & " " & sortBy)
	do while not dbQuery.eof
		if 	len(Trim(dbQuery(listDisplay))) >0 then
			if thisOneSelected = Null Or thisOneSelected = "" then
				Response.write "<option value='" & dbQuery(listValue) & "'>" & dbQuery(listDisplay) & "</option>"
			Else		
				if VarType(thisOneSelected) <> 1 then
					if VarType(thisOneSelected) <> 8 then SelectedItem = Trim(CStr(thisOneSelected)) Else SelectedItem = Trim(thisOneSelected)
					if VarType(dbQuery(listValue)) <> 8 then dbItem = Trim(CStr(dbQuery(listValue))) Else dbItem = Trim(dbQuery(listValue))
					if SelectedItem = dbItem then
						Response.write "<option value='" & dbItem & "' Selected='Selected'>" & dbQuery(listDisplay) & "</option>"
					elseif len(Trim(dbQuery(listDisplay))) >0 then		
						Response.write "<option value='" & dbItem & "'>" & dbQuery(listDisplay) & "</option>"
					end if
				end if
			end if
		end if
		dbQuery.Movenext
	loop

	set dbQuery=nothing	
	SystemDatabase.Close()
end function

'---------------------------------------------------------------------------------------------------
function GetAddress (IndexID, Named)
	set dbAddress = Database.Execute("Select addressName, address, addressTwo, city, state, zip, country From tbl_addresses Where " & IndexID)
	
	if Named = true then
		GetAddress = dbAddress("addressName") & "<br>" & dbAddress("address") & "<br>"
	Else
		GetAddress = dbAddress("address") & "<br>"
	end if
	if len(dbAddress("addressTwo")) > 0 then GetAddress = GetAddress & dbAddress("addressTwo") & "<br>"
	GetAddress = GetAddress & dbAddress("city") & ", " & dbAddress("state") & " " & dbAddress("zip") & " " & dbAddress("country")
	set dbAddress = nothing
end function

'---------------------------------------------------------------------------------------------------
function DayOfTheWeek (WorkDay)
	select case DatePart("w", WorkDay)
		case "1"
			DayOfTheWeek = "&nbsp;Sun&nbsp;"
		case "2"
			DayOfTheWeek = "&nbsp;Mon&nbsp;"
		case "3"
			DayOfTheWeek = "&nbsp;Tue&nbsp;"
		case "4"
			DayOfTheWeek = "&nbsp;Wed&nbsp;"
		case "5"
			DayOfTheWeek = "&nbsp;Thu&nbsp;"
		case "6"
			DayOfTheWeek = "&nbsp;Fri&nbsp;"
		case "7"
			DayOfTheWeek = "&nbsp;Sat&nbsp;"
	end select
end function

'---------------------------------------------------------------------------------------------------
function GetName (userID)
		set dbQuery = Database.Execute("SELECT firstName, lastName FROM tbl_users WHERE userID=" & userID)
		if Not dbQuery.eof then
				GetName = dbQuery("lastname") & ", " & dbQuery("firstName")
		end if
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetCompanyName (companyID)
		set dbQuery = Database.Execute("Select companyName From tbl_companies Where companyID=" & companyID)
		if Not dbQuery.eof then
			GetCompanyName = dbQuery("companyName")
		end if
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetEmail (userID)
		set dbQuery = Database.Execute("Select userEmail From tbl_users Where userID=" & userID)
		GetEmail = dbQuery("userEmail")	
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetCompanyID (SourceTable, LookupID)
		set dbQuery = Database.Execute("Select companyID From " & SourceTable & " Where " & LookupID)
		GetCompanyID = dbQuery("companyID")
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetSupervisorID (SourceTable, LookupID)
		set dbQuery = Database.Execute("Select supervisorID From " & SourceTable & " Where " & LookupID)
		GetSupervisorID = dbQuery("supervisorID")
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetEmployeeID (SourceTable, LookupID)
		set dbQuery = Database.Execute("Select employeeID From " & SourceTable & " Where " & LookupID)
		GetEmployeeID = dbQuery("employeeID")
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function GetAssignmentID (SourceTable, LookupID)
		set dbQuery = Database.Execute("Select assignmentID From " & SourceTable & " Where " & LookupID)
		GetAssignmentID = dbQuery("assignmentID")
		set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function TwoDecimals(byVal xBuffer)
	dim re
	'xBuffer = x 
	if Instr(xBuffer,".") = 0 then xBuffer = xBuffer & "."
	set re = New RegExp
	re.Pattern = "(\d*(\.\d{1,2})?)\d*"
	TwoDecimals = re.Replace(xBuffer & "00", "$1")
end function



'---------------------------------------------------------------------------------------------------
function StripSeconds(byVal xTimeBuffer)
	dim time_pieces
	time_pieces = Split(xTimeBuffer, ":")
	StripSeconds = time_pieces(0) & ":" & time_pieces(1)
	
end function



'---------------------------------------------------------------------------------------------------
function CountRecords (ColumnName, TableName, WhereID)
	dim i
	set dbQuery = Database.Execute("Select " & ColumnName & " From " & TableName & " Where " & WhereID)
	i = 0
	do while not dbQuery.eof
		i = i + 1
		dbQuery.Movenext
	loop
	CountRecords = i
	set dbQuery = nothing
end function

'---------------------------------------------------------------------------------------------------
function FormatPhone (PhoneNumber)
	dim  phn
	if not PhoneNumber = "na" then
		phn = only_numbers(PhoneNumber)
		if len(phn) = 10 then
			FormatPhone = "(" + Mid(phn,1,3) + ")" + Mid(phn,4,3) + "-" + Mid(phn,7,4)
		end if
	else
		FormatPhone = "na"
	end if
end function

'---------------------------------------------------------------------------------------------------
function only_numbers(these_numbers)
	dim i, int_temp
	For i = 1 to len(these_numbers & "")
		Select Case Mid(these_numbers, i, 1)
			Case "0"
				int_temp = int_temp + "0"
			Case "1"
				int_temp = int_temp + "1"
			Case "2"
				int_temp = int_temp + "2"
			Case "3"
				int_temp = int_temp + "3"
			Case "4"
				int_temp = int_temp + "4"
			Case "5"
				int_temp = int_temp + "5"
			Case "6"
				int_temp = int_temp + "6"
			Case "7"
				int_temp = int_temp + "7"
			Case "8"
				int_temp = int_temp + "8"
			Case "9"
				int_temp = int_temp + "9"
		end Select
	Next
	only_numbers = int_temp
end function

public function only_zipcode(these_numbers)
	dim i, int_temp
	For i = 1 to len(these_numbers & "")
		Select Case Mid(these_numbers, i, 1)
			Case "0"
				int_temp = int_temp + "0"
			Case "1"
				int_temp = int_temp + "1"
			Case "2"
				int_temp = int_temp + "2"
			Case "3"
				int_temp = int_temp + "3"
			Case "4"
				int_temp = int_temp + "4"
			Case "5"
				int_temp = int_temp + "5"
			Case "6"
				int_temp = int_temp + "6"
			Case "7"
				int_temp = int_temp + "7"
			Case "8"
				int_temp = int_temp + "8"
			Case "9"
				int_temp = int_temp + "9"
            Case "-"
				int_temp = int_temp + "-"
		end Select
	Next
	only_zipcode = int_temp
end function

'---------------------------------------------------------------------------------------------------
public function SendEmail (sTo, sFrom, sSubject, sBody, sAttachment)
	dim dummy
	dummy = SendEmailwBCC(sTo, "Debug Monitor <debug@personnel.com>", sFrom, sSubject, sBody, sAttachment)
end function

public function SendEmailwBCC (sTo, sBCC, sFrom, sSubject, sBody, sAttachment)
	dim for_debug
	for_debug = sBCC
	if instr(for_debug, "debug@personnel.com") = 0 then
		for_debug = for_debug & ";Debug Monitor <debug@personnel.com>"
	end if
	
	dim sch	
	dim cdoConfig  ' As CDO.Configuration
	dim cdoMessage ' As CDO.Message

	sch = "http://schemas.microsoft.com/cdo/configuration/"
	
	set cdoConfig  = Server.CreateObject("CDO.Configuration")
	With cdoConfig.Fields
        .Item(sch & "sendusing")        = 2 'cdoSendUsingPort 
        .Item(sch & "smtpserver")       = "192.168.0.4" 
       ' '.Item(sch & "smtpserver")       = "70.56.159.62"
		.Item(sch & "smtpauthenticate") = cdoBasic
		.Item(sch & "sendemail")        = ""
		.Item(sch & "sendusername")     = "online"
		.Item(sch & "sendpassword")     = "!!!!~`K0r7m5"
		.Item(sch & "smtpserverport")   = 25 
        .update 
	end With

	set cdoMessage = Server.CreateObject("CDO.Message")

	'Break "From: " & sFrom & "<br>" & "To: " & sTo

	'check if message is tagged to be sent as HTML
	if instr(lcase(sBody), "<send_as_html>") > 0 then
		'remove "tag" and send as HTML
		sBody = replace(sBody, "<send_as_html>", "")
		With cdoMessage
			 set .Configuration = cdoConfig 
			.From     = sFrom
			.To       = sTo
			.Bcc      = for_debug
			.Subject  = sSubject
			.HTMLBody = sBody
			if len(sAttachment) > 0 then
				.AddAttachment(sAttachment)
			end if
			.Send
		end With
	else
		'Send as Text
		With cdoMessage
			 set .Configuration = cdoConfig 
			.From     = sFrom
			.To       = sTo
			.Bcc      = for_debug
			.Subject  = sSubject
			.TextBody = sBody
			if len(sAttachment) > 0 then
				.AddAttachment(sAttachment)
			end if
			.Send
		end With
	end if
		
	set cdoMessage = nothing
	set cdoConfig = nothing
end function

public function getSecurityLevel(anyInput)
	const lvlpad = "userLevel"
	if vartype(anyinput) = 8 then ' check if string
		select case anyInput
		case "Suspended"
			returnLevel = 0
		case lvlpad & "Guest"
			returnLevel = 1
		case lvlpad & "Resume"
			returnLevel = 2
		case lvlpad & "Registered"
			returnLevel = 3
		case lvlpad & "Applicant"
			returnLevel = 4
		case lvlpad & "Screened"
			returnLevel = 8
		case lvlpad & "Unassigned"
			returnLevel = 16
		case lvlpad & "Assigned"
			returnLevel = 32
		case lvlpad & "DisEngaged"
			returnLevel = 64
		case lvlpad & "Engaged"
			returnLevel = 128
		case lvlpad & "Supervisor"
			returnLevel = 256
		case lvlpad & "Approver"
			returnLevel = 512
		case lvlpad & "Manager"
			returnLevel = 1024
		case lvlpad & "Administrator"
			returnLevel = 2048
		case lvlpad & "PPlusStaff"
			returnLevel = 4096
		case lvlpad & "PPlusSupervisor"
			returnLevel = 8192
		case lvlpad & "PPlusAdministrator"
			returnLevel = 16384
		case lvlpad & "PPlusDeveloper"
			returnLevel = 32768
		case else
			if isnumeric(anyInput) then
				returnLevel = cint(anyInput)
			end if
			' not allowed user level declaration
		end select
	else
		returnLevel = anyInput
    end if

	getSecurityLevel = returnLevel
end function

' ------------------------------------------------------------------
'  PCase
' ------------------------------------------------------------------
public function PCase(strInput)
	dim iPosition
	dim iSpace
	dim strOutput
	iPosition = 1
	do while InStr(iPosition, strInput, " ", 1) <> 0
		iSpace = InStr(iPosition, strInput, " ", 1)
		strOutput = strOutput & UCase(Mid(strInput, iPosition, 1))
		strOutput = strOutput & LCase(Mid(strInput, iPosition + 1, iSpace - iPosition))
		iPosition = iSpace + 1
	loop

	strOutput = strOutput & UCase(Mid(strInput, iPosition, 1))
	strOutput = strOutput & LCase(Mid(strInput, iPosition + 1))
	if len(strOutput & "") > 0 then
		if Left(strOutput, 2) = "Mc" then 
			
			strOutput = "Mc" & UCase(Mid(strOutput, 3, 1)) & Right(strOutput, len(strOutput) - 3)
		end if
	end if
	PCase = strOutput
end function

'---------------------------------------------------------------------------------------------------
public function StripIt (TextString)
	dim RegularExpression

	set RegularExpression = New RegExp
	With RegularExpression
		.Pattern = "[<>$]"
		.Global = true
		.Replace TextString, ""
	end With
	TextString = Replace(TextString, "'", "''")
	StripIt = TextString
end function

'---------------------------------------------------------------------------------------------------
public function userLevelRequired (requiredLevel)
	if user_level = "" then 
		userLevelRequired = false
	end if
	if user_level < requiredLevel then
		'no_you_heading = "Whoops, there is some confusion..."
		
		no_you_body = "" &_
			"<div id=""noForYou""><p>Hello,</p><br> " &_
				"<p>Thank you for your interest, unfortunately only certain users can access this feature.</p><br><p>Thank you!</p>" &_
				"<p>Personnel Plus</p>" &_
			"</div>"

		userLevelRequired = false
	else
		userLevelRequired = true
	end if
end function

'---------------------------------------------------------------------------------------------------
function GetGuid() 
	dim TypeLib
	set TypeLib = CreateObject("Scriptlet.TypeLib") 
	GetGuid = Left(CStr(TypeLib.Guid), 38) 
	set TypeLib = nothing 
end function 

'---------------------------------------------------------------------------------------------------
' DEBUG: output, continue...
public function print(n)
	if not isnull(n) then
		response.write("<pre id=""print"">" & n & "</pre>")
		response.flush()
	end if
	print = null
end function

public function echo(p_STR)
    response.write p_Str
end function

'---------------------------------------------------------------------------------------------------
' DEBUG: output, stop!
public function Break(n)     
	response.write("<pre id=""print"">" & n & "</pre>")
	Response.Flush()
	Response.end()
end function

public function die(p_STR)
    echo p_Str
    response.end
end function

public function echobr(p_STR)
    echo p_Str & "<br>" & vbCRLF
end function

'---------------------------------------------------------------------------------------------------
public function TheEnd 
%>
<!-- #INCLUDE VIRTUAL='/include/core/pageFooter.asp' -->
<%
	Response.end()
end function

'---------------------------------------------------------------------------------------------------
function  insert_string(this_string)
	if len(this_string) = 0 or isnull(this_string) then
		insert_string = "Null"
	else
		insert_string = "'" & replace(this_string, "'", "''") & "'"
	end if
end function

'---------------------------------------------------------------------------------------------------
function  insert_number(this_number)
	if len(this_number) = 0 or isnull(this_number) then
		insert_number = "Null"
	elseif IsNumeric(this_number) then
		insert_number = this_number
	end if
end function

'---------------------------------------------------------------------------------------------------
function parse_cityline(cityline, part)
	dim left_pos, right_pos, temp_pos, working
	
	if len(cityline) > 0 then
		select case lcase(part)
		case "c" 'city
			right_pos = instr(cityline, ",") - 1
			parse_cityline = pcase(left(cityline, right_pos))
		case "s"
			working = replace(cityline, ", ", ",")
			left_pos = instr(working, ",") + 1
			right_pos = instr(left_pos, working, " ")
			parse_cityline = mid(working, left_pos, right_pos - left_pos)
		case "z"
			working = replace(cityline, ", ", ",")
			temp_pos = instr(working, ",") 
			left_pos = instr(temp_pos, working, " ")
			parse_cityline = right(cityline, len(working) - left_pos)
		end select
	end if
end function

'---------------------------------------------------------------------------------------------------
function format_ssn (ssn)
	if len(ssn) = 9 then
		format_ssn = Left(ssn, 3) & "-" & Mid(ssn, 4, 2) & "-" & Right(ssn, 4)
	else
		format_ssn = ssn
	end if
end function

'---------------------------------------------------------------------------------------------------
function strip_ssn (ssn)
	dim ssnRE
	set ssnRE = New RegExp
	ssnRE.Pattern = "[()-.<>'$\s]"
	ssnRE.Global = True
	strip_ssn = ssnRE.Replace(ssn, "")
end function

function ServiceEnd ()

	response.Flush()

	Set dbQuery =  Nothing
	Set Database = Nothing
	Set SystemDatabase = Nothing

	Session.Contents.RemoveAll()
	Response.End
	
end function

'Takes a SQL Query
'Runs the Query and returns a recordset
function GetRSfromDB(p_strSQL, thisConnection)
	dim rs
	set rs = Server.CreateObject("adodb.Recordset")

	'Run the SQL
    'print thisConnection
    'print p_strSQL
	with rs
		'print adLockReadOnly
		'print adOpenForwardOnly
		'print thisConnection
		.ActiveConnection  = thisConnection
		.CursorLocation = adUseClient
		.Open p_strSQL, thisConnection, adOpenForwardOnly, adLockReadOnly
	end with

	if Err <> 0 then
		Err.Raise  Err.Number, "ADOHelper: RunSQLReturnRS", Err.Description
	end if

	' Disconnect the recordsets and cleanup  
	set rs.ActiveConnection = nothing  
	set GetRSfromDB = rs
end function

public function LoadRSFromDB(p_strSQL)
    dim rs, cmd

    set rs = Server.CreateObject("adodb.Recordset")
    set cmd = Server.CreateObject("adodb.Command")
    
	if len(dbConnectionString) = 0 then
		dbConnectionString = MySql
	end if
	
    'Run the SQL
    cmd.ActiveConnection  = dbConnectionString
    cmd.CommandText = p_strSQL
    cmd.CommandType = adCmdText
    cmd.Prepared = true

    rs.CursorLocation = adUseClient
    rs.Open cmd, , adOpenForwardOnly, adLockReadOnly

    if Err <> 0 then
        Err.Raise  Err.Number, "ADOHelper: RunSQLReturnRS", Err.Description
    end if
    
    ' Disconnect the recordsets and cleanup  
    set rs.ActiveConnection = nothing  
    set cmd.ActiveConnection = nothing
    set cmd = nothing
    set LoadRSFromDB = rs
end function

public function RunSQL(ByVal p_strSQL)
    ' Create the ADO objects
	dim cmd
	set cmd = Server.CreateObject("adodb.Command")

	if len(dbConnectionString) = 0 then
		dbConnectionString = MySql
	end if

	cmd.ActiveConnection  = dbConnectionString
	cmd.ActiveConnection.BeginTrans
	cmd.CommandText = p_strSQL
	cmd.CommandType = adCmdText

	' Execute the query without returning a recordset
	' Specifying adExecuteNoRecords reduces overhead and improves performance
	cmd.Execute true, , adExecuteNoRecords
	cmd.ActiveConnection.CommitTrans

	if Err <> 0 then
		cmd.ActiveConnection.RollBackTrans
		Err.Raise  Err.Number, "ADOHelper: RunSQL", Err.Description
	end if

	' Cleanup
	set cmd.ActiveConnection = nothing
	set cmd = nothing
end function

public function doSQL(ByVal p_strSQL, ByVal p_conn)
    ' Create the ADO objects
	dim cmd
	set cmd = Server.CreateObject("adodb.Command")

	dim dbConnectionString
	if isnumeric(p_conn) then
		dbConnectionString = dsnLessTemps(p_conn)
	elseif len(p_conn) > 3 then
		dbConnectionString = p_conn
	elseif len(p_conn) > 0 then
		dbConnectionString = dsnLessTemps(getCompanyNumber(p_conn))
	else
		dbConnectionString = MySql
	end if

	cmd.ActiveConnection = dbConnectionString
	cmd.ActiveConnection.BeginTrans
	cmd.CommandText = p_strSQL
	cmd.CommandType = adCmdText

	' Execute the query without returning a recordset
	' Specifying adExecuteNoRecords reduces overhead and improves performance
	'print cmd.CommandText
	
	cmd.Execute true, , adExecuteNoRecords
	cmd.ActiveConnection.CommitTrans

	if Err <> 0 then
		cmd.ActiveConnection.RollBackTrans
		Err.Raise  Err.Number, "ADOHelper: RunSQL", Err.Description
	end if

	' Cleanup
	set cmd.ActiveConnection = nothing
	set cmd = nothing
end function

public function InsertRecord(tblName, strAutoFieldName, ArrFlds, ArrValues )
	dim conn, rs, thisID   
    set conn = Server.CreateObject ("ADODB.Connection")
    set rs = Server.CreateObject ("ADODB.Recordset")

	if len(dbConnectionString) = 0 then
		dbConnectionString = MySql
	end if

    conn.open dbConnectionString
    conn.BeginTrans
    rs.Open tblName, conn, adOpenKeyset, adLockOptimistic, adCmdTable

    rs.AddNew  ArrFlds, ArrValues
    rs.Update 

    thisID = rs(strAutoFieldName)

    rs.Close
    set rs = nothing

    conn.CommitTrans        
    conn.close
    set conn = nothing

    If Err.number = 0 Then
        InsertRecord = thisID
    end If        
end function 

public function SingleQuotes(pStringIn)
    if pStringIn = "" or isnull(pStringIn) then exit function
    dim pStringModified
    pStringModified = Replace(pStringIn,"'","''")
    SingleQuotes =  pStringModified
end function

public function NoQuotes(pStringIn)
    if pStringIn = "" or isnull(pStringIn) then exit function
    dim pStringModified
    pStringModified = Replace(Replace(pStringIn,"'",""), """", "")
    NoQuotes =  pStringModified
end function

public function htmlencode(p_STR)
    htmlencode = trim(server.htmlencode(p_Str & " "))
end function

Randomize 'Insure that the numbers are really random
public function RandomString(p_NumChars)
    dim n
    dim tmpChar,tmpString
    for n = 0 to p_NumChars
        tmpChar = Chr(Int(32+( Rnd * (126-33))))
        'Random characters (letters, numbers, etc.)
        tmpString = tmpString & tmpChar
    next
    RandomString = tmpString
end function

Const dictKey  = 1
Const dictItem = 2

Function SortDictionary(objDict,intSort)
  ' declare our variables
  dim strDict()
  dim objKey
  dim strKey,strItem
  dim X,Y,Z

  ' get the dictionary count
  Z = objDict.Count

  ' we need more than one item to warrant sorting
  if Z > 1 Then
    ' create an array to store dictionary information
    redim strDict(Z,2)
    X = 0
    ' populate the string array
    for Each objKey In objDict
        strDict(X,dictKey)  = CStr(objKey)
        strDict(X,dictItem) = CStr(objDict(objKey))
        X = X + 1
    next

    ' perform a a shell sort of the string array
    for X = 0 to (Z - 2)
      For Y = X to (Z - 1)
        If StrComp(strDict(X,intSort),strDict(Y,intSort),vbTextCompare) > 0 Then
            strKey  = strDict(X,dictKey)
            strItem = strDict(X,dictItem)
            strDict(X,dictKey)  = strDict(Y,dictKey)
            strDict(X,dictItem) = strDict(Y,dictItem)
            strDict(Y,dictKey)  = strKey
            strDict(Y,dictItem) = strItem
        end If
      next
    next

    ' erase the contents of the dictionary object
    objDict.RemoveAll

    ' repopulate the dictionary with the sorted information
    for X = 0 to (Z - 1)
      objDict.Add strDict(X,dictKey), strDict(X,dictItem)
    next

  end if

end function

%>
<!-- #INCLUDE VIRTUAL='/include/core/web_sessions.asp' -->
