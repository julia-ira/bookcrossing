import { Component } from 'angular2/core';
import { Book } from './book';
import { BooklistService } from './booklist.service';

@Component({
	selector: 'book-list',
	templateUrl: 'app/js/booklist/booklist.component.html',
	providers:   [BooklistService]
})

export class BooklistComponent {
	name = 'bookcrossing';
	errorMessage: string;
	books: Book[];
	mode = 'Observable';
	constructor (private booklistService: BooklistService) {}
	ngOnInit() { this.getBooks(); }
	getBooks() {
		this.booklistService.getBooks()
		.subscribe(
			books => this.books = books,
			error =>  this.errorMessage = <any>error);
	}
}
