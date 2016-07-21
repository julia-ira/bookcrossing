import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class AuthenticationService {
    constructor(private http: Http) {}
    private booksUrl = 'http://bookcrossing.esy.es/books'; // URL to web API

    login(email: any, password:any): Observable < Response > {
        var headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded');
        return this.http.post(this.booksUrl, book, {
            headers: headers
        });
    }

    register() {
        // todo
    }
}
