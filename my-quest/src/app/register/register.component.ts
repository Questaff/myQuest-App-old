import { PostService } from './../services/post.service';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
})
export class RegisterComponent implements OnInit {

  errorMsg: any;
  successMsg: any;
  msg: string;
  error: boolean = false;
  form: boolean = true;
  success: boolean = false;
  result : object;

  data: object = {
    lname : null,
    fname : null,
    phone : null,
    mail : null,
    username : null,
    pass : null,
  }

  registerForm = new FormGroup({
    lname: new FormControl(null,[
    ]),
    fname: new FormControl(null,[
    ]),
    phone: new FormControl(null,[
      Validators.pattern('^0[1-6]{1}(([0-9]{2}){4})|((\s[0-9]{2}){4})|((-[0-9]{2}){4})$')
    ]),
    mail: new FormControl(null,[
      Validators.required,
      Validators.pattern('^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$')
    ]),
    cmail: new FormControl(null,[
      Validators.required,
      Validators.pattern('^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$')
    ]),
    username: new FormControl(null,[
      Validators.required
    ]),
    pass: new FormControl(null,[
      Validators.required,
      //Validators.pattern('^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$')
    ]),
    cpass: new FormControl(null,[
      Validators.required,
      //Validators.pattern('^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$')
    ])
  });

  submitRegister() {
    this.error = false;

    if (this.registerForm.controls.mail.value != this.registerForm.controls.cmail.value) {
      this.error = true;
      return this.errorMsg = [{message : 'Les emails ne correspondent pas entre eux'}];
    }

    if (this.registerForm.controls.pass.value != this.registerForm.controls.cpass.value) {
      this.error = true;
      return this.errorMsg = [{message : 'Les mots de passe ne correspondent pas entre eux'}];
    }

    this.data["lname"] = this.registerForm.controls.lname.value;
    this.data["fname"] = this.registerForm.controls.fname.value;
    this.data["phone"] = this.registerForm.controls.phone.value;
    this.data["mail"] =  this.registerForm.controls.mail.value;
    this.data["username"] = this.registerForm.controls.username.value;
    this.data["pass"] =  this.registerForm.controls.pass.value;
    this.data["action"] = "newUser";

    this.PostService.postData(this.data).subscribe(respose => {
      this.result = respose;
      if (this.result["success"] == false) {
        this.error = true;
        return this.errorMsg = [{message : this.result["message"]}];
      }
      if (this.result["success"] == true) {
        this.form = false;
        this.success = true;
      }
    })
  }

  constructor(
    private PostService:PostService
  ) { }

  ngOnInit() {}

}
