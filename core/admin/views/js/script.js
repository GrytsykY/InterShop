document.querySelector('.sitemap-button').onclick = (e) => {

	e.preventDefault();

	createSiteMap();
}

let links_counter = 0;

function createSiteMap() {

	links_counter++;

	Ajax({data: {ajax: 'sitemap',links_counter: links_counter}})
		.then((res) => {
			console.log('успех - ' + res);
		})
		.catch((res) => {

			console.log('ошибка' + res);

		});
}