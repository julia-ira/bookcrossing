import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class AuthenticationService {
    constructor(private http: Http) {}
    private authurl = 'http://bookcrossing.esy.es/login'; // URL to web API

    login(email: string, password: string): Observable < Response > {
        let authHeaders = new Headers();
        authHeaders.append("Authorization", "Basic " + btoa(email + ":" + password));
        console.log("Basic " + btoa(email + ":" + password));
        authHeaders.append("Content-Type", "application/x-www-form-urlencoded");
        let data = "username=" + email + "&password=" + password;
        return this.http.post(this.authurl, data, {
            headers: authHeaders
        });
    }

    register() {
        // todo
    }
}
