import { AppComponent } from './../app.component';
import { PostService } from './../services/post.service';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-feed',
  templateUrl: './feed.component.html',
  styleUrls: ['./feed.component.scss'],
})
export class FeedComponent implements OnInit {

  result: any;
  data: object ={
    
  };
  dataAddQuest: object ={
    tokenUser : null,
    idQuest : null,
  };
  dataQuest: object = {
    tokenUser : null,
    title : null,
    content : null,
  };

  constructor(
    private PostService:PostService,
    private router:Router,
    private AppComponent:AppComponent,
    public alertController: AlertController,
  ) { }

  questForm = new FormGroup({
    title: new FormControl(null,[
      Validators.required,
      //Validators.pattern()
    ]),
    content: new FormControl(null,[
      Validators.required,
      //Validators.pattern()
    ]),
  });

  ngOnInit() {
    this.AppComponent.checkTokenConnect();
    this.getQuest()
  }

  getQuest() {
    this.data = {};
    this.data["action"] = "getQuest";
    this.PostService.postData(this.data).subscribe(response => {
      this.result = response["message"];
      return this.result;
    })
  }

  addQuest(id:number) {
    let result;
    this.data = {};
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["idQuest"] = id;
    this.data["action"] = "questToUser";
    this.PostService.postData(this.data).subscribe(response => {
      result = response;
      return this.result;
    })
  }

  submitQuest() {
    let result;
    this.data={};
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["title"] = this.questForm.controls.title.value;
    this.data["content"] = this.questForm.controls.content.value;
    this.data["action"] = "newQuest";    

    this.PostService.postData(this.data).subscribe(response => {
      result = response;
      if (result["success"] == true) {
        this.questForm.setValue({
          title:"",
          content:""
        })
        this.getQuest()
      }
    })
  }

  async openAccept(id:number) {
    const alert = await this.alertController.create({
      header: 'Acceptation de quete',
      message: 'Voulez-vous accepter cette quete ?',
      buttons: [
        {
          text: 'Non',
          role: 'cancel',
          handler: () => {
          }
        },{
          text: 'Oui',
          handler: () => {
            this.addQuest(id)
          }
        }
      ]
    });

    await alert.present();
  }

}