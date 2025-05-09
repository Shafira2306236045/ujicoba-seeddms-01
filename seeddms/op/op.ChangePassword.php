<?php
//    MyDMS. Document Management System
//    Copyright (C) 2002-2005 Markus Westphal
//    Copyright (C) 2006-2008 Malcolm Cowe
//    Copyright (C) 2010-2016 Uwe Steinmann
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

include("../inc/inc.Settings.php");
include("../inc/inc.Utils.php");
include("../inc/inc.LogInit.php");
include("../inc/inc.Language.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.ClassSession.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.ClassUI.php");

/* Check if the form data comes from a trusted request */
if(!checkFormKey('changepassword')) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_request_token"))),getMLText("invalid_request_token"));
}

if (isset($_POST["hash"])) {
	$hash = $_POST["hash"];
}
if (isset($_POST["newpassword"])) {
	$newpassword = $_POST["newpassword"];
}
if (isset($_POST["newpasswordrepeat"])) {
	$newpasswordrepeat = $_POST["newpasswordrepeat"];
}

if (empty($newpassword) || empty($newpasswordrepeat) || $newpassword != $newpasswordrepeat) {
	UI::exitError(getMLText("password_mismatch_error_title"),getMLText("password_mismatch_error"));
}

$user = $dms->checkPasswordRequest($hash);
if($user) {
	$user->setPwd(seed_pass_hash($newpassword));
	$dms->deletePasswordRequest($hash);
	header('Location: ../out/out.Login.php');
	exit;
}

UI::exitError(getMLText("password_forgotten_invalid_hash_title"),getMLText("password_forgotten_invalid_hash"));

