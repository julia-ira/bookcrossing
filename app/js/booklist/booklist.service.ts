import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';
import { AuthHttp, tokenNotExpired } from 'angular2-jwt';
import { Book } from './book';

@Injectable()
export class BooklistService {
    constructor(private http: Http, private authHttp: AuthHttp) {}
    private booksUrl = 'http://bookcrossing.esy.es/books'; // URL to web API

    getBooks(): Observable < Response > {
        return this.http.get(this.booksUrl);
    }

    addBook(book: any): Observable < Response > {
        var headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded');
        return this.http.post(this.booksUrl, book, {
            headers: headers
        });
    }

    updateBook() {

    }

    getBook(id: number | string): Observable < any > {
        return this.http.get(this.booksUrl + "/" + id)
            .map(res => res.json());
    }

    getSecuredData(): Observable < Response > {
        if (tokenNotExpired())
            return this.authHttp.get('http://bookcrossing.esy.es/securetest');
    }

}
