import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ROUTER_DIRECTIVES } from '@angular/router';
import { Book } from './book';
import { BooklistService } from './booklist.service';

@Component({
    selector: 'book-detail',
    templateUrl: 'app/js/booklist/bookdetail.component.html',
    providers: [BooklistService],
    directives: [ROUTER_DIRECTIVES]
})

export class BookDetailComponent {
    book: Book;
    id: number;
    mode = 'Observable';

    constructor(private route: ActivatedRoute, private router: Router, private booklistService: BooklistService) {
        this.route
            .params
            .subscribe(params => {
                this.id = +params['id'];
                this.booklistService.getBook(this.id)
                    .subscribe(
                        data => {
                            this.book = new Book(data);
                            console.log("Data...");
                            console.log(data);
                        }
                    );
                console.log("Book...");
                console.log(this.book);
            });
    }

    ngOnInit() {
        console.log("in bookdetail constructor...");
        console.log(this.id);
        /*this.booklistService.getBook(this.id)
            .subscribe(
                data => {
                    this.book = new Book(data);
                    console.log("Data...");
                    console.log(data);
                }
            );
        console.log("Book...");
        console.log(this.book);*/
    }

    goBack() { this.router.navigate(['/booklist']); }
}
