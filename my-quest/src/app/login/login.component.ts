import { AppComponent } from './../app.component';
import { PostService } from './../services/post.service';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent implements OnInit {

  data: object = {
    username : null,
    pass : null,
  }
  result : object = {
    message: null
  };

  loginForm = new FormGroup({
    username: new FormControl(null,[
      Validators.required,
    ]),
    pass: new FormControl(null,[
      Validators.required,
      //Validators.pattern(regex password)
    ]),
  });

  submitLogin() {
    this.data["username"] = this.loginForm.controls.username.value;
    this.data["pass"] =  this.loginForm.controls.pass.value;
    this.data["action"] = "connection";

    this.PostService.postData(this.data).subscribe(response => {
      this.result = response;
      localStorage.setItem('token',this.result["message"]);
      if (this.result["success"] == true) {
        this.AppComponent.showMenu();
        this.router.navigate(['quest']);
      }
    });
    
  }

  constructor(
    private PostService:PostService,
    private router:Router,
    private AppComponent:AppComponent
  ) { }

  ngOnInit() {
  }

}
