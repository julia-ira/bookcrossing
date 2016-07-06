import { Injectable }     from 'angular2/core';
import { Http, Response } from 'angular2/http';
import 'rxjs/Rx';
import { Observable }     from 'rxjs/Observable';

import { Book } from './book';

@Injectable()
export class BooklistService {
  constructor (private http: Http) {}
  private booksUrl = 'http://bookcrossing.esy.es/books';  // URL to web API
  getBooks (): Observable<Book[]> {
    return this.http.get(this.booksUrl)
                    .map(this.extractData)
                    .catch(this.handleError);
  }
  private extractData(res: Response) {
    let books = res.json();
    let result:Array<Book> = [];
    if(books){
      for (var book in books) {
        if (books.hasOwnProperty(book)) {
          result.push(<Book> books[book]);
        }
      }
    }
    return result;
  }
  private handleError (error: any) {
    // In a real world app, we might use a remote logging infrastructure
    // We'd also dig deeper into the error to get a better message
    let errMsg = (error.message) ? error.message :
      error.status ? `${error.status} - ${error.statusText}` : 'Server error';
    console.error(errMsg); // log to console instead
    return Observable.throw(errMsg);
  }
}