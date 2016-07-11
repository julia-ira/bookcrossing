import { Component } from 'angular2/core';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouteConfig } from 'angular2/router';
import { Book } from './book';
import { BooklistService } from './booklist.service';

@Component({
	selector : 'book-detail',
    templateUrl: 'app/js/booklist/bookdetail.component.html',
    providers: [BooklistService]
})

export class BookDetailComponent {
    book: Book;
    id: number;
    mode = 'Observable';

    constructor(params: RouteParams, private router: Router, private booklistService: BooklistService) {
        this.id = +params.get('id');
    }

    ngOnInit() {
        console.log("in bookdetail constructor...");
        console.log(this.id);
        this.booklistService.getBook(this.id)
            .subscribe(
                data => { this.book = new Book(data);
                    console.log("Data...");
                    console.log(data); }
            );
        console.log("Book...");
        console.log(this.book);
    }

    goBack() { this.router.navigate(['/booklist']); }
}
