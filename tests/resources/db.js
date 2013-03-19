db.user.insert({ name: "Alice", age: 20, createAt: new Date() });
db.user.insert({ name: "Bob", age: 17, createAt: new Date() });
db.user.insert({ name: "Carl", age: 30, createAt: new Date() });

db.article.insert({
	title: "My cool article",
	content: "Cool",
	tags: ["one", "two"],
	grades: {
		value: 5,
		novelty: 10
	},
	createTime: new Date(),
	photo: {
		url: "http://example.com",
		desc: "Cool photo"
	},
	author: {
		name: "Piter",
		address: {
			country: "USA",
			city: "NY"
		},
		phones: [
			{
				type: "TYPE_HOME",
				value: "911"
			},
			{
				type: "TYPE_WORK",
				value: "12345"
			}
		]
	},
	comments: [
		{ text: "first comment" },
		{ text: "second comment" }
	]
});
