<?php
echo "<style>
html, body {
	overflow: hidden !important;
}
#perfmatters-script-manager-wrapper {
	display: none;
	position: fixed;
	z-index: 99999999;
	top: 32px;
	bottom: 0px;
	left: 0px;
	right: 0px;
	background: rgba(0,0,0,0.5);
	overflow-y: auto;
}
#perfmatters-script-manager {
	background: #EEF2F5;
	padding: 20px 20px 20px 320px;
	font-size: 14px;
	line-height: 1.5em;
	color: #4a545a;
	min-height: 100%;
}
#perfmatters-script-manager a {
	color: #4A89DD;
	text-decoration: none;
	border: none;
}
#perfmatters-script-manager label {
	float: none;
	opacity: 1;
}
#perfmatters-script-manager-header {
	position: fixed;
	top: 32px;
	left: 0px;
	bottom: 0px;
	width: 300px;
	background: #282E34;
}
#perfmatters-script-manager-header #perfmatters-logo {
	display: block;
	margin: 20px auto;
	width: 200px;
}
#perfmatters-script-manager-header h2 {
	font-size: 24px;
	margin: 0px 0px 10px 0px;
	color: #4a545a;
	font-weight: bold;
}
#perfmatters-script-manager-header h2 span {
	background: #ED5464;
	color: #ffffff;
	padding: 5px;
	vertical-align: middle;
	font-size: 10px;
	margin-left: 5px;
}
#perfmatters-script-manager-header p {
	font-size: 14px;
	color: #4a545a;
	font-style: italic;
	margin: 0px auto 15px auto;
}
#perfmatters-script-manager-close {
	position: absolute;
	top: 0px;
	right: 0px;
	height: 26px;
	width: 26px;
}
#perfmatters-script-manager-close img {
	height: 26px;
	width: 26px;
}
#perfmatters-script-manager-tabs button {
	display: block;
	float: left;
	padding: 15px 20px;
	width: 100%;
	font-size: 17px;
	line-height: normal;
	text-align: left;
	background: #222222;
	color: #ffffff;
	font-weight: normal;
	border: none;
	cursor: pointer;
	border-radius: 0px;
}
#perfmatters-script-manager-tabs {
	overflow: hidden;
}
#perfmatters-script-manager-tabs button span {
	display: block;
	font-size: 12px;
	margin-top: 5px;
}
#perfmatters-script-manager-tabs button.active {
	background: #4A89DD;
	color: #ffffff;
}
#perfmatters-script-manager-tabs button:hover {
	background: #ffffff;
	color: #4A89DD;
}
#perfmatters-script-manager-tabs button.active:hover {
	background: #4A89DD;
	color: #ffffff;
}
#perfmatters-script-manager-disclaimer {
	background: #ffffff;
	padding: 20px 20px 10px 20px;
	margin: 0px 0px 20px 0px;
}
#perfmatters-script-manager-disclaimer p {
	font-size: 14px;
	margin: 0px 0px 10px 0px;
}
#perfmatters-script-manager-container {
	max-width: 1000px;
	margin: 0px auto 50px auto;
}
#perfmatters-script-manager-container .perfmatters-script-manager-title-bar {
	margin-bottom: 13px;
	text-align: center;
}
#perfmatters-script-manager-container .perfmatters-script-manager-title-bar h1 {
	font-size: 28px;
	line-height: normal;
	font-weight: 400;
	margin: 0px;
	color: #282E34;
}
#perfmatters-script-manager-container .perfmatters-script-manager-title-bar p {
	margin: 0px;
	color: #282E34;
}
#perfmatters-script-manager h3 {
	padding: 10px;
	margin: 0px;
	font-size: 18px;
	background: #282E34;
	color: #ffffff;
	text-transform: capitalize;
	font-weight: 400;
}
.perfmatters-script-manager-group {
	box-shadow: 0 1px 6px 0 rgba(40,46,52,.3);
	margin: 0px 0px 20px 0px;
}
.perfmatters-script-manager-group h4 {
	font-size: 24px;
	line-height: 40px;
	margin: 0px;
	padding: 10px;
	background: #edf3f9;
	font-weight: 700;
}
.perfmatters-script-manager-section {
	padding: 10px;
	background: #ffffff;
	margin: 0px 0px 0px 0px;
	overflow: hidden;
}
#perfmatters-script-manager table {
	table-layout: fixed;
	width: 100%;
	margin: 0px;
	padding: 0px;
	border: none;
	text-align: left;
	font-size: 14px;
	border-collapse: collapse;
}
#perfmatters-script-manager table thead {
	background: none;
	color: #282E34;
	font-weight: bold;
	border: none;
}
#perfmatters-script-manager table thead tr {
	border: none;
	border-bottom: 2px solid #dddddd;
}
#perfmatters-script-manager table thead th {
	font-size: 14px;
	padding: 8px 5px;
	vertical-align: middle;
	border: none;
}
#perfmatters-script-manager table tr {
	border: none;
	border-bottom: 1px solid #eeeeee;
	background: #ffffff;
}
#perfmatters-script-manager table tbody tr:last-child {
	border-bottom: 0px;
}
#perfmatters-script-manager table td {
	padding: 8px 5px;
	border: none;
	vertical-align: top;
	font-size: 14px;
}
#perfmatters-script-manager table td.perfmatters-script-manager-type {
	font-size: 14px;
	text-align: center;
	padding-top: 16px;
	text-transform: uppercase;
}
#perfmatters-script-manager table td.perfmatters-script-manager-size {
	font-size: 14px;
	text-align: center;
	padding-top: 16px;
}
#perfmatters-script-manager table td.perfmatters-script-manager-script a {
	white-space: nowrap;
}
#perfmatters-script-manager .perfmatters-script-manager-script span {
	display: block;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	font-size: 14px;
	font-weight: bold;
	margin-bottom: 3px;
}
#perfmatters-script-manager .perfmatters-script-manager-script a {
	display: block;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	font-size: 10px;
	color: #4A89DD;
	line-height: normal;
}
#perfmatters-script-manager .perfmatters-script-manager-disable, #perfmatters-script-manager .perfmatters-script-manager-enable {
	margin: 10px 0px 0px 0px; 
}
#perfmatters-script-manager table .perfmatters-script-manager-disable *:after, #perfmatters-script-manager table .perfmatters-script-manager-disable *:before {
	display: none;
}
#perfmatters-script-manager select {
	display: block;
	position: relative;
	height: auto;
	width: auto;
	background: #ffffff;
	background-color: #ffffff;
	padding: 7px 10px;
	margin: 0px;
	font-size: 14px;
	appearance: menulist;
	-webkit-appearance: menulist;
	-moz-appearance: menulist;
}
#perfmatters-script-manager select.perfmatters-disable-select, #perfmatters-script-manager select.perfmatters-status-select {
	border: 2px solid #27ae60;
}
#perfmatters-script-manager select.perfmatters-disable-select.everywhere, #perfmatters-script-manager select.perfmatters-status-select.disabled {
	border: 2px solid #ED5464;
}
#perfmatters-script-manager select.perfmatters-disable-select.current {
	border: 2px solid #f1c40f;
}
#perfmatters-script-manager select.perfmatters-disable-select.hide {
	display: none;
}
#perfmatters-script-manager .perfmatters-script-manager-enable-placeholder {
	color: #bbbbbb;
	font-style: italic;
	font-size: 14px;
}
#perfmatters-script-manager input[type='radio'] {
	position: relative;
	display: inline-block;
	margin: 0px 3px 0px 0px;
	vertical-align: middle;
	opacity: 1;
	z-index: 0;
	appearance: radio;
	-webkit-appearance: radio;
	-moz-appearance: radio;
	vertical-align: baseline;
	height: auto;
	width: auto;
	font-size: 16px;
}
#perfmatters-script-manager input[type='checkbox'] {
	position: relative;
	display: inline-block;
	margin: 0px 3px 0px 0px;
	vertical-align: middle;
	opacity: 1;
	z-index: 0;
	appearance: checkbox;
	-webkit-appearance: checkbox;
	-moz-appearance: checkbox;
	vertical-align: baseline;
	height: auto;
	width: auto;
	font-size: 16px;
}
#perfmatters-script-manager .perfmatters-script-manager-controls {
	text-align: left;
}
#perfmatters-script-manager .perfmatters-script-manager-controls label {
	display: inline-block;
	margin: 0px 10px 0px 0px;
	width: auto;
	font-size: 12px;
}
#perfmatters-script-manager .perfmatters-script-manager-toolbar {
	position: fixed;
	bottom: 0px;
	left: 300px;
	right: 17px;
	padding: 10px 20px;
	height: 50px;
	background: rgba(238,242,245,0.95);
	box-sizing: content-box;
}
#perfmatters-script-manager .perfmatters-script-manager-toolbar-container {
	display: flex;
	align-items: center;
	justify-content: space-between;
	max-width: 1000px;
	margin: 0px auto;
	height: 50px;
}
#perfmatters-script-manager input[type='submit'] {
	background: #4a89dd;
	color: #ffffff;
	cursor: pointer;
	border: none;
	font-size: 14px;
	/*margin: 10px auto 0px auto;*/
	margin: 0px;
	/*padding: 15px 20px;*/
	padding: 0px 20px;
	height: 50px;
	line-height: 50px;
	font-weight: 700;
	width: auto;
	border-radius: 0px;
}
#perfmatters-script-manager input[type='submit']:hover {
	background: #5A93E0;
}
#script-manager-settings input[type='submit'] {
	float: left;
}
#perfmatters-script-manager input[type='submit'].pmsm-reset {
	/*float: right;*/
	float: none;
	background: #ED5464;
	height: 35px;
    line-height: 35px;
    padding: 0px 10px;
    font-size: 12px;
    margin-bottom: 5px;
}
#perfmatters-script-manager input[type='submit'].pmsm-reset:hover {
	background: #c14552;
}
/* On/Off Toggle Switch */
#perfmatters-script-manager .perfmatters-script-manager-switch {
	position: relative;
	display: block;
	/*width: 48px;
	height: 28px;*/
	width: 76px;
	height: 40px;
	font-size: 1px;
}
#perfmatters-script-manager .perfmatters-script-manager-switch input[type='checkbox'] {
	display: block;
	margin: 0px;
}
#perfmatters-script-manager .perfmatters-script-manager-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #27ae60;
	-webkit-transition: .4s;
	transition: .4s;
}
#perfmatters-script-manager .perfmatters-script-manager-slider:before {
	position: absolute;
	content: '';
	/*height: 20px;
	width: 20px;
	right: 4px;
	bottom: 4px;*/
	width: 30px;
	top: 5px;
	right: 5px;
	bottom: 5px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
}
#perfmatters-script-manager .perfmatters-script-manager-switch input:checked + .perfmatters-script-manager-slider {
	background-color: #ED5464;
}
#perfmatters-script-manager .perfmatters-script-manager-switch input:focus + .perfmatters-script-manager-slider {
	box-shadow: 0 0 1px #ED5464;
}
#perfmatters-script-manager .perfmatters-script-manager-switch input:checked + .perfmatters-script-manager-slider:before {
	/*-webkit-transform: translateX(-20px);
	-ms-transform: translateX(-20px);
	transform: translateX(-20px);*/
	-webkit-transform: translateX(-36px);
	-ms-transform: translateX(-36px);
	transform: translateX(-36px);
}

#perfmatters-script-manager .perfmatters-script-manager-slider:after {
	content:'" . __('ON', 'perfmatters') . "';
	color: white;
	display: block;
	position: absolute;
	transform: translate(-50%,-50%);
	top: 50%;
	left: 27%;
	font-size: 10px;
	font-family: Verdana, sans-serif;
}

#perfmatters-script-manager .perfmatters-script-manager-switch input:checked + .perfmatters-script-manager-slider:after {  
	left: unset;
	right: 0%;
  	content:'" . __('OFF', 'perfmatters') . "';
}

#perfmatters-script-manager .perfmatters-script-manager-assets-disabled p {
	margin: 20px 0px 0px 0px;
	text-align: center;
	font-size: 12px;
	padding: 10px 0px 0px 0px;
	border-top: 1px solid #f8f8f8;
}
/*Settings View*/
#script-manager-settings table th {
	width: 200px;
	vertical-align: top;
	border: none;
}
#script-manager-settings .switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 28px;
  font-size: 1px;
}
#script-manager-settings .switch input {
  display: block;
  margin: 0px;
}
#script-manager-settings .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
#script-manager-settings .slider:before {
  position: absolute;
  content: '';
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
#script-manager-settings input:checked + .slider {
  background-color: #2196F3;
}

#script-manager-settings input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
#script-manager-settings input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}
#jquery-message {
	font-size: 12px;
	font-style: italic;
	color: #27ae60;
	margin-top: 5px;
}
@media (max-width: 800px) {
	#perfmatters-script-manager {
		padding-left: 20px;
	}
	#perfmatters-script-manager-header {
		position: relative;
		top: 0px;
		width: 100%;
		overflow: hidden;
		margin-bottom: 20px;
	}
	#perfmatters-script-manager .perfmatters-script-manager-toolbar {
		left: 0px;
	}
}
</style>";