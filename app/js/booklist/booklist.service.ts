import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';
import { AuthHttp, tokenNotExpired } from 'angular2-jwt';

@Injectable()
export class BooklistService {

    constructor(private http: Http, private authHttp: AuthHttp) {}

    private booksUrl = 'http://bookcrossing.esy.es/books';
    private securedbooksUrl = 'http://bookcrossing.esy.es/securetest';

    getBooks(): Observable < Response > {
        return this.http.get(this.booksUrl);
    }

    getSecuredData(): Observable < Response > {
        if (tokenNotExpired())
            return this.authHttp.get(this.securedbooksUrl);
    }
}
