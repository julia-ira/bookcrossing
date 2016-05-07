var serverUrl = "/api/";

Book = Backbone.Model.extend({
	defaults: {
		id : null,
		title : null,
		author : null,
		year: null,
		photo: null,
		condition: null,
		status: null,
		user: ''
	},
	idAttribute: "id",
    initialize: function () {
        console.log('Book has been initialized');
        console.log(this);
        this.on("invalid", function (model, error) {
            console.log("Houston, we have a problem: " + error)
        });
    },
    constructor: function (attributes, options) {
        console.log('Book\'s constructor had been called');
        Backbone.Model.apply(this, arguments);
    },
    validate: function (attr) {
    },
	urlRoot: serverUrl + 'book'
});

BookCollection = Backbone.Collection.extend({
	model: Book,
	url: serverUrl + 'books'
});

BookView = Backbone.View.extend({
	tagName: 'tr',
	initialize: function() {
		this.render = _.bind(this.render, this);
		this.template = _.template($('#book-item').html());
		console.log(this);
		this.model.bind('change', this.render);
	},
	events: {
	},
	render: function() {
		this.$el.html(this.template(this.model.attributes));
		return this;
	}
});

var book = new Book({ id: "1" });
book.fetch({
	success: function(response,xhr) {
		console.log("Inside success");
		console.log(response);
	},
	error: function (errorResponse) {
	    console.log(errorResponse)
	}
});

$(document).ready(function() {
	var bookview = new BookView({
		el: $('#books'),
		model: book
	});
});