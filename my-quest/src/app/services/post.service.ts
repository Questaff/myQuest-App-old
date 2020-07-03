import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PostService {

  url: string = "";

  constructor(
    private http:HttpClient
  ) { }

  postData(data){
    return this.http.post(this.url,data);
  }
  
}