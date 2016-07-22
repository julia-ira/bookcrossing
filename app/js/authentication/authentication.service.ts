import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class AuthenticationService {
    constructor(private http: Http) {}
    private authurl = 'http://bookcrossing.esy.es/login'; // URL to web API

    login(email: string, password: string): Observable < Response > {
        let headers = new Headers();
        headers.append("Authorization", "Basic " + btoa(email + ":" + password));
        headers.append("Content-Type", "application/x-www-form-urlencoded");
        return this.http.post(this.authurl, {
            headers: headers
        });
    }

    register() {
        // todo
    }
}
