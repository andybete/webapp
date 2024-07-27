<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function login(Request $request)  {

$err = "";
if (isset($_POST['submit'])) {
  $password = $request['pass'];
  $username = $request['uname'];

if (empty($username) || empty($password)) {
    $err = "Please fill in both username and password.";
} else {
    $sql = "SELECT * FROM approvecandidate WHERE username='$username' AND password='$password'";
    $results = DB::select($sql);
    if (count($results)==1) {
        // Redirect to candidate.php with the updated values
        return view('candidate', compact('username'));
       
    } elseif (count($results)==0) {
        $sql = "SELECT * FROM new_center WHERE username='$username' AND password='$password' AND approve=1";
        $results = DB::select($sql);
        if (count($results)==1) {
            $institution = $results['institution'];
            $city = $results['city'];
           
            return view('school', compact('username','institution','city'));
        }
    } 
	if ($username == 'admin' && $password == 'admin') {
        return view('admin');
    } 
	if (empty($username) || empty($password)) {
    $err = "Please fill in both username and password.";
    } else {
        $sql = "SELECT * FROM staff WHERE username = '$username'";
        $results = DB::select($sql);
        if (count($results)==1) {
            $activate = $results['activate'];
            $role = $results['role'];

            if ($activate == 0) {
                return view('fill_form', compact('username'));
            } elseif ($activate == 'activated' && $role == 'EPS') {
                return view('eps');
            } elseif ($role == 'EES') {
                return view('ees');
            }
        }
    }
    $err = "Incorrect username or password.";
    } return view('home',compact('err'));
}
    }
    public function logout()  {
        return true;
    }
    public function register()  {
        return true;
    }
}
