export class Book {
    id: number;
    title: string;
    author: string;
    year: number;
    state: string;
    status: string;
    owner: any;
    user_id:number;

    constructor(obj: any) {
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                this[prop] = obj[prop];
            }
        }
    }

    getBookPostData() {
        let data = "";
        for (var prop in this) {
            if (this.hasOwnProperty(prop)) {
                data += prop + '=' + this[prop] + '&';
            }
        }
        return data;
    }
}
