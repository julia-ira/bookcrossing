import { Injectable } from 'angular2/core';
import { Http, Response, Headers } from 'angular2/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';
import { Book } from './book';

@Injectable()
export class BooklistService {
    constructor(private http: Http) {}
    private booksUrl = 'http://bookcrossing.esy.es/books'; // URL to web API

    getBooks(): Observable < Book[] > {
        return this.http.get(this.booksUrl)
            .map(res => {
                let books = res.json();
                let result: Array < Book > = [];
                if (books) {
                    for (var book in books) {
                        if (books.hasOwnProperty(book)) {
                            result.push( < Book > books[book]);
                        }
                    }
                }
                return result;
            });
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
}
