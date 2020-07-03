import { AlertController } from '@ionic/angular';
import { PostService } from './../services/post.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-response',
  templateUrl: './response.component.html',
  styleUrls: ['./response.component.scss'],
})
export class ResponseComponent implements OnInit {

  idQuest : number = null;
  data : object = {
  
  };
  myObject : object;

  constructor(
    private PostService:PostService,
    public alertController: AlertController,
  ) { }

  ngOnInit() {
    this.idQuest = history.state.data;
    this.loadResponce();
  }

  loadResponce(){
    this.data = {};
    this.data["action"] = "lookFinish";
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["idQuest"] = this.idQuest;
    this.PostService.postData(this.data).subscribe(response => {
      this.myObject = response["message"];
    })
  }

  async openAccept(username: string) {
    const alert = await this.alertController.create({
      header: 'Validation',
      message: 'Voulez-vous confirmer que l\'utilisateur ait fini sa quête',
      buttons: [
        {
          text: 'Non',
          role: 'cancel',
          handler: () => {
          }
        }, {
          text: 'oui',
          handler: () => {
            this.accept(username);
          }
        }
      ]
    });

    await alert.present();
  }

  async openRefuse(username: string) {
    const alert = await this.alertController.create({
      header: 'Refus',
      message: 'Voulez-vous refuser la validation de la quête de l\'utilisateur',
      buttons: [
        {
          text: 'Non',
          role: 'cancel',
          handler: () => {
          }
        }, {
          text: 'oui',
          handler: () => {
            this.refuse(username);
          }
        }
      ]
    });

    await alert.present();
  }

  accept(username: string) {
    this.data = {};
    this.data["action"] = "questFinish";
    this.data["idQuest"] = this.idQuest;
    this.data["username"] = username;

    this.PostService.postData(this.data).subscribe(response => {
      this.myObject = response;
      this.loadResponce();
    })
  }

  refuse(username: string) {
    this.data = {};
    this.data["action"] = "questRefuse";
    this.data["idQuest"] = this.idQuest;
    this.data["username"] = username;

    this.PostService.postData(this.data).subscribe(response => {
      this.myObject = response;
      this.loadResponce();
    })
  }

}
