import { Router, ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { PostService } from '../services/post.service';
import { AppComponent } from '../app.component';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-accepted',
  templateUrl: './accepted.component.html',
  styleUrls: ['./accepted.component.scss'],
})
export class AcceptedComponent implements OnInit {

  questAccept: any;
  myQuest: any;
  result: any;
  data: object = {
    
  }
  currentModal = null;

  constructor(
    private PostService:PostService,
    private AppComponent:AppComponent,
    public alertController: AlertController,
    private router:Router,
    private activeRoute:ActivatedRoute,
  ) { }

  ngOnInit() {   
    this.activeRoute.params.subscribe(routeParams => {
      this.AppComponent.checkTokenConnect(); 
      this.myQuests();
      this.questAccepts();
  });
  }

  myQuests() {
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["action"] = "myQuest";
    this.PostService.postData(this.data).subscribe(response => {
      this.myQuest = response["message"];
      return this.myQuest;
    })
  }

  questAccepts() {
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["action"] = "questAccept";
    this.PostService.postData(this.data).subscribe(response => {
      this.questAccept = response["message"];
      return this.questAccept;
    })
  }

  finish(id: number) {
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["idQuest"] = id;
    this.data["action"] = "finishQuest";

    this.PostService.postData(this.data).subscribe(response => {
      this.result = response;
      this.questAccepts();
      return this.result;
    })
    
  }

  remove(id: number) {
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["idQuest"] = id;
    this.data["action"] = "removeQuest";

    this.PostService.postData(this.data).subscribe(response => {
      this.result = response;
      this.myQuests();
      this.questAccepts();
      return this.result;
    })
  }

  cancelQuest(id: number){
    this.data["tokenUser"] = localStorage.getItem('token');
    this.data["idQuest"] = id;
    this.data["action"] = "cancelQuest";

    this.PostService.postData(this.data).subscribe(response => {
      this.result = response;
      this.myQuests();
      this.questAccepts();
      return this.result;
    })
  }
  
  async openAlert(id: number) {
    const alert = await this.alertController.create({
      header: 'ATTENTION !',
      message: 'Si vous supprimez la quête, les personnes n\'ayant pas fini la quête ne pouront plus l\'accepter et la réaliser',
      buttons: [
        {
          text: 'Annuler',
          role: 'cancel',
          handler: () => {
          }
        }, {
          text: 'Confirmer',
          handler: () => {
            this.remove(id)
          }
        }
      ]
    });

    await alert.present();
  }

  async openCancel(id:number) {
    const alert = await this.alertController.create({
      header: 'Annuler votre quête',
      message: 'Voulez-vous annuler la quête ?',
      buttons: [
        {
          text: 'Non',
          role: 'cancel',
          handler: () => {
          }
        },{
          text: 'Oui',
          handler: () => {
            this.cancelQuest(id);  
          }
        }
      ]
    });
    await alert.present();
  }

  async openFinish(id: number) {
    const alert = await this.alertController.create({
      header: 'Terminer la quête !',
      message: 'Voulez-vous finir cette quête, si vous la finisez, une demande au créateur de la quête serra envoyée et celui-ci decidera si vous avez correctement fini votre quete',
      buttons: [
        {
          text: 'Non',
          role: 'cancel',
          handler: () => {
          }
        }, {
          text: 'Oui',
          handler: () => {
            this.finish(id);
          }
        }
      ]
    });

    await alert.present();
  }


  response(id: number) {
    this.router.navigate(['qestsResponse'],{state:{data: id}})
  }

}