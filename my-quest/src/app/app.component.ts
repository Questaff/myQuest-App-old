import { PostService } from './services/post.service';
import { Component, OnInit } from '@angular/core';

import { Platform } from '@ionic/angular';
import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss']
})
export class AppComponent implements OnInit {
  showFirstMenu : boolean = true;
  showSndMenu : boolean = false;
  data: Object = {
    tokenUser: null,
    action: null
  }
  result: any;

  public selectedIndex = 0;
  public appPages = [
    {
      title: 'Accueil',
      url: '',
      icon: 'home-outline'
    },{
      title: 'Connexion',
      url: '/login',
      icon: 'person-outline'
    },
    {
      title: 'Inscription',
      url: '/register',
      icon: 'person-add-outline'
    }
  ];
  public appsPages = [
    {
      title: 'Quêtes',
      url: '/quest',
      icon: 'bookmarks-outline'
    },
    {
      title: 'Mes quêtes',
      url: '/myQuests',
      icon: 'bookmark-outline'
    },
    {
      title: 'Paramètres',
      url: '/setting',
      icon: 'settings-outline'
    }
  ];

  constructor(
    private platform: Platform,
    private splashScreen: SplashScreen,
    private statusBar: StatusBar,
    private router:Router,
    private PostService:PostService
  ) {
    this.initializeApp();
  }

  initializeApp() {
    this.platform.ready().then(() => {
      this.statusBar.styleDefault();
      this.splashScreen.hide();
    });
  }

  showMenu() {
    if (localStorage.getItem('token') == null) {
      this.showFirstMenu = true;
      this.showSndMenu = false;
    } else {
      this.showFirstMenu = false;
      this.showSndMenu = true;
    }
  }

  checkTokenConnect() {
    if (localStorage.getItem('token') == null) {
      this.router.navigate(['']);
      this.showMenu();
    } else {
      this.validToken();
    }
  }

  validToken() {
    this.data['token'] = localStorage.getItem('token');
    this.data['action'] = "checkToken";

    this.PostService.postData(this.data).subscribe(response => {
      this.result = response;
      if (this.result["success"] == false) {
        localStorage.removeItem('token');
        this.router.navigate(['']);
        this.showMenu();
      }
    });
  }

  ngOnInit() {
    this.showMenu();
  }
}
