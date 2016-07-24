import { Component } from '@angular/core';
import { ROUTER_DIRECTIVES, Router } from '@angular/router';
import { Book } from './book';
import { BooklistService } from './booklist.service';
import { BookDetailComponent } from './bookdetail.component';
import { BookFormComponent } from './book-form.component';

@Component({
    directives: [BookFormComponent, ROUTER_DIRECTIVES],
    selector: 'book-list',
    templateUrl: 'app/js/booklist/booklist.component.html',
    providers: [BooklistService]
})

export class BooklistComponent {
    name = 'bookcrossing';
    books: Book[];
    mode = 'Observable';
    securedData: any;

    constructor(private booklistService: BooklistService, private router: Router) { console.log("in booklisst constructor..."); }

    ngOnInit() { this.getBooks(); }

    getBooks() {
        this.booklistService.getBooks()
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
            })
            .subscribe(
                books => this.books = books
            );
    }

    addBook() {

    }

    getSecuredData() {
        this.booklistService.getSecuredData().subscribe(res => this.securedData = res.text());
    }

    logout() {
        localStorage.removeItem('id_token');
        this.router.navigate(['/login']);
    }
}
