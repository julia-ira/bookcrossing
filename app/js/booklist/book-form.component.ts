import { Component } from 'angular2/core';
import { FORM_DIRECTIVES } from 'angular2/common';
import { Http } from 'angular2/http';
import { Book } from './book';
import { BooklistService } from './booklist.service';

@Component({
    selector: 'book-form',
    templateUrl: 'app/js/booklist/book-form.component.html',
    //styleUrls: ['simple-form.component.css'],
    directives: [FORM_DIRECTIVES],
    providers: [BooklistService]
})
export class BookFormComponent {
    book: Book;

    constructor(private http: Http, private booklistService: BooklistService) {}

    submitBook(book: any) {
        let data: any = new Book(book).getBookPostData();
        console.log("Before submit");
        console.log(data);
        this.booklistService.addBook(data).subscribe(
            data => {
                alert('The book is created!');
                console.log("response:");
                console.log(data.json());
            },
            error => alert(error.json().message)
        )
    }
}
