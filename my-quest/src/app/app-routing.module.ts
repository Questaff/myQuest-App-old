import { ResponseComponent } from './response/response.component';
import { FeedComponent } from './feed/feed.component';
import { HomeComponent } from './home/home.component';
import { RegisterComponent } from './register/register.component';
import { LoginComponent } from './login/login.component';
import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { SettingComponent } from './setting/setting.component';
import { AcceptedComponent } from './accepted/accepted.component';

const routes: Routes = [
  {
    path: '',
    component: HomeComponent
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'register',
    component: RegisterComponent
  },
  {
    path: 'quest',
    component: FeedComponent
  },
  {
    path: 'setting',
    component: SettingComponent
  },
  {
    path: 'myQuests',
    component: AcceptedComponent
  },
  {
    path: 'qestsResponse',
    component: ResponseComponent
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
