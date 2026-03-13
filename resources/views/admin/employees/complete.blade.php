<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Complete Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:linear-gradient(90deg,#f8cdda,#f88fa6);
}

/* wrapper */

.profile-wrapper{
display:flex;
justify-content:center;
padding:20px;
}

/* card */

.profile-card{
width:100%;
max-width:440px;
border-radius:18px;
border:none;
}

/* labels */

.form-label{
font-weight:600;
font-size:14px;
}

/* inputs */

.form-control,
.form-select{
border-radius:8px;
font-size:16px; /* prevents mobile zoom */
}

/* textarea */

textarea{
resize:none;
}

/* button */

#submitBtn{
border-radius:30px;
font-weight:600;
}

/* password rules */

.password-rules{
font-size:13px;
margin-top:6px;
}

.password-rules span{
display:block;
}

.valid{
color:#198754;
}

.invalid{
color:#dc3545;
}

/* toggle */

.toggle-password{
cursor:pointer;
}

/* MOBILE UI */

@media(max-width:576px){

.profile-wrapper{
padding:10px;
}

.profile-card{
max-width:100%;
}

.card-body{
padding:18px !important;
}

h3{
font-size:20px;
}

.form-label{
font-size:13px;
}

}

</style>

</head>

<body>

<div class="profile-wrapper">

<div class="card profile-card shadow">

<div class="card-body p-4">

<form method="POST" action="{{ route('employees.complete.store', $user->remember_token) }}">

@csrf
@include('partials.flash-messages')

<div class="text-center mb-3">

<i class="bi bi-person-circle display-6 text-primary"></i>

<h3 class="fw-bold text-primary mt-2 mb-1">
Complete Your Profile
</h3>

<p class="text-muted small">
Welcome! Please complete your profile.
</p>

@if($user->employee_id)

<div class="alert alert-info py-2 small">
<i class="bi bi-badge-check"></i>
Your Employee ID:
<strong>{{ $user->employee_id }}</strong>
</div>

@endif

</div>

<!-- PASSWORD -->

<div class="mb-3">

<label class="form-label">Password</label>

<div class="position-relative">

<input
id="password"
name="password"
type="password"
class="form-control pe-5"
autocomplete="new-password">

<span
class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
data-target="password">

<i class="bi bi-eye-slash"></i>

</span>

</div>

<div class="password-rules">

<span id="rule-length" class="invalid">
❌ At least 6 characters
</span>

<span id="rule-upper" class="invalid">
❌ Must contain uppercase
</span>

<span id="rule-lower" class="invalid">
❌ Must contain lowercase
</span>

<span id="rule-number" class="invalid">
❌ Must contain number
</span>

</div>

</div>

<!-- CONFIRM PASSWORD -->

<div class="mb-3">

<label class="form-label">Confirm Password</label>

<input
id="password_confirmation"
name="password_confirmation"
type="password"
class="form-control">

<small id="confirmPasswordFeedback"></small>

</div>

<!-- PHONE -->

<div class="mb-3">

<label class="form-label">Phone Number</label>

<div class="input-group">

<span class="input-group-text">+63</span>

<input
id="phone"
name="phone"
type="tel"
class="form-control"
value="{{ old('phone', $user->phone ? substr($user->phone,3) : '') }}"
placeholder="9XXXXXXXXX"
maxlength="10">

</div>

<small class="text-muted">
Enter 10 digit number starting with 9
</small>

</div>

<!-- GENDER -->

<div class="mb-3">

<label class="form-label">Gender</label>

<select name="gender" class="form-select">

<option value="">Select Gender</option>
<option value="male">Male</option>
<option value="female">Female</option>

</select>

</div>

<!-- ADDRESS -->

<div class="mb-3">

<label class="form-label">Address</label>

<textarea
name="address"
rows="2"
class="form-control"
placeholder="Enter your complete address"></textarea>

</div>

<div class="d-grid mb-3">

<button type="submit" class="btn btn-primary" id="submitBtn">

<i class="bi bi-check-circle"></i>
Complete Profile

</button>

</div>

<div class="alert alert-info small py-2 mb-0">

<i class="bi bi-info-circle"></i>

After completing your profile, you'll be redirected to login.

</div>

</form>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded",function(){

const password=document.getElementById("password")
const confirm=document.getElementById("password_confirmation")
const feedback=document.getElementById("confirmPasswordFeedback")

const lengthRule=document.getElementById("rule-length")
const upperRule=document.getElementById("rule-upper")
const lowerRule=document.getElementById("rule-lower")
const numberRule=document.getElementById("rule-number")

password.addEventListener("input",function(){

const val=password.value

// length
if(val.length>=6){
lengthRule.className="valid"
lengthRule.innerHTML="✔ At least 6 characters"
}else{
lengthRule.className="invalid"
lengthRule.innerHTML="❌ At least 6 characters"
}

// uppercase
if(/[A-Z]/.test(val)){
upperRule.className="valid"
upperRule.innerHTML="✔ Has uppercase"
}else{
upperRule.className="invalid"
upperRule.innerHTML="❌ Must contain uppercase"
}

// lowercase
if(/[a-z]/.test(val)){
lowerRule.className="valid"
lowerRule.innerHTML="✔ Has lowercase"
}else{
lowerRule.className="invalid"
lowerRule.innerHTML="❌ Must contain lowercase"
}

// number
if(/[0-9]/.test(val)){
numberRule.className="valid"
numberRule.innerHTML="✔ Has number"
}else{
numberRule.className="invalid"
numberRule.innerHTML="❌ Must contain number"
}

})

// confirm password

confirm.addEventListener("input",function(){

if(confirm.value.length===0){
feedback.innerHTML=""
return
}

if(confirm.value===password.value){
feedback.innerHTML="✔ Passwords match"
feedback.className="text-success small"
}else{
feedback.innerHTML="❌ Passwords do not match"
feedback.className="text-danger small"
}

})

// phone cleaner

const phone=document.getElementById("phone")

phone.addEventListener("input",function(e){

let value=e.target.value.replace(/\D/g,'')

if(value.length>0 && value[0]!=='9'){
value='9'+value.substring(1)
}

if(value.length>10){
value=value.substring(0,10)
}

e.target.value=value

})

// toggle password

document.querySelectorAll(".toggle-password").forEach(btn=>{

btn.addEventListener("click",function(){

const input=document.getElementById(this.dataset.target)
const icon=this.querySelector("i")

if(input.type==="password"){
input.type="text"
icon.classList.replace("bi-eye-slash","bi-eye")
}else{
input.type="password"
icon.classList.replace("bi-eye","bi-eye-slash")
}

})

})

})

</script>

</body>
</html>
