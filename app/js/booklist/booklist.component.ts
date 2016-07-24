import { Component } from '@angular/core';
import { ROUTER_DIRECTIVES, Router } from '@angular/router';
import { BooklistService } from './booklist.service';

@Component({
    selector: 'book-list',
    templateUrl: 'app/js/booklist/booklist.component.html',
    providers: [BooklistService]
})

export class BooklistComponent {
    name = 'bookcrossing';
    books: any[];

    constructor(private booklistService: BooklistService, private router: Router) {}

    getBooks() {
        this.booklistService.getBooks()
            .subscribe(
                res => { this.books = res.json();
                    console.log(this.books); }
            );
    }
}
