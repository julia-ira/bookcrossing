import { Component } from 'angular2/core';
import { Book } from './book';
import { Router, ROUTER_DIRECTIVES, RouteConfig } from 'angular2/router';
import { BooklistService } from './booklist.service';
import { BookDetailComponent } from './bookdetail.component';
import {BookFormComponent} from './book-form.component';

@Component({
    directives: [ROUTER_DIRECTIVES, BookFormComponent],
    selector: 'book-list',
    templateUrl: 'app/js/booklist/booklist.component.html',
    providers: [BooklistService]
})

@RouteConfig([
    { path: '/', component: BookDetailComponent, as: 'BookDetailComponent', useAsDefault: true },
    { path: '/:id', as: 'Bookdetail', component: BookDetailComponent }
])

export class BooklistComponent {
    name = 'bookcrossing';
    books: Book[];
    mode = 'Observable';

    constructor(private router: Router, private booklistService: BooklistService) {console.log("in booklisst constructor...");}

    ngOnInit() { this.getBooks(); }

    getBooks() {
        this.booklistService.getBooks()
            .subscribe(
                books => this.books = books
            );
    }

    addBook(){
    	
    }
}
