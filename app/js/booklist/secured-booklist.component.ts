import { Component } from '@angular/core';
import { ROUTER_DIRECTIVES, Router } from '@angular/router';
import { BooklistService } from './booklist.service';

@Component({
    selector: 'book-list',
    templateUrl: 'app/js/booklist/secured-booklist.component.html',
    providers: [BooklistService]
})

export class SecuredBooklistComponent {
    name = 'bookcrossing';
    books: any[];

    constructor(private booklistService: BooklistService, private router: Router) {}

    getSecuredData() {
        this.booklistService.getSecuredData().subscribe(res => this.books = res.json());
    }

    logout() {
        localStorage.removeItem('id_token');
        this.router.navigate(['/login']);
    }
}
