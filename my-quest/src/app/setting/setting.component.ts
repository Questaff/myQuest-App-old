import { Component, OnInit } from '@angular/core';
import { AppComponent } from '../app.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-setting',
  templateUrl: './setting.component.html',
  styleUrls: ['./setting.component.scss'],
})
export class SettingComponent implements OnInit {

  constructor(
    private AppComponent:AppComponent,
    private router:Router,
  ) { }

  ngOnInit() {
    this.AppComponent.checkTokenConnect();
  }

  deco() {
    localStorage.removeItem('token');
    this.AppComponent.showMenu();
    this.router.navigate(['']);
  }

}
