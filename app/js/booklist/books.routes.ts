import { RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist.component';
import { BookDetailComponent } from './bookdetail.component';

export const bookRoutes: RouterConfig = [
    { path: 'books', component: BooklistComponent },
    { path: 'books/:id', component: BookDetailComponent }
];
