import { Component } from '@angular/core';
import { ROUTER_DIRECTIVES }    from '@angular/router';
import { Book } from './book';
import { BooklistService } from './booklist.service';
import { BookDetailComponent } from './bookdetail.component';
import { BookFormComponent } from './book-form.component';

@Component({
    directives: [BookFormComponent,ROUTER_DIRECTIVES],
    selector: 'book-list',
    templateUrl: 'app/js/booklist/booklist.component.html',
    providers: [BooklistService]
})

export class BooklistComponent {
    name = 'bookcrossing';
    books: Book[];
    mode = 'Observable';

    constructor(private booklistService: BooklistService) { console.log("in booklisst constructor..."); }

    ngOnInit() { this.getBooks(); }

    getBooks() {
        this.booklistService.getBooks()
            .subscribe(
                books => this.books = books
            );
    }

    addBook() {

    }
}
