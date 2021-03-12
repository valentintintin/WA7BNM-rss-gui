const parser = new (require('rss-parser'));
const cheerio = require('cheerio');
const https = require('https');
const moment = require('moment');
const fs = require('fs');

const items = [];

(async () => {
	const feed = await parser.parseURL('https://www.contestcalendar.com/calendar.rss');

	feed.items.forEach(item => {
		https.get(item.link, (resp) => {
			let data = '';

			resp.on('data', (chunk) => {
				data += chunk;
			});

			resp.on('end', () => {
				const $ = cheerio.load(data);
				const elements = $('#main table tr td');
			
				$('#main table tr').each((i, el) => {				
					const splited = $(el).text().trim().split(':');
					
					if (i === 0) {
						item.name = splited[0];
					} else if (splited.length > 1) {
						item[splited[0].toLowerCase()] = splited.splice(1).join(':');
					}
				});
				
				item.datesString = item.content.split('to');
				item.dates = [];
				item.datesString.forEach((date, i) => {
					item.datesString[i] = date.trim();
					item.dates.push(moment(date, "HHmmZ, Ddd GG YYYY"));
				});
				
				item.dateStartString = item.datesString[0];
				item.dateEndString = item.datesString[item.datesString.length - 1];
				
				item.dateStart = item.dates[0];
				item.dateEnd = item.dates[item.dates.length - 1];
				
				if (item.mode && (
					!item.mode.includes('SSB')
				)) {
					return;	
				}
				
				if (item['geographic focus'] && (
					!item['geographic focus'].includes('Europe')
					&& !item['geographic focus'].includes('Worldwide')
				)) {
					return;	
				}
				
				if (item.participation && (
					!item.participation.includes('Europe')
					&& !item.participation.includes('Worldwide')
				)) {
					return;	
				}
				
				console.log(item);
				
				items.push(item);
		
				fs.writeFile('contest.json', JSON.stringify(items.sort((a, b) => a.dateStart < b.dateStart ? -1 : 1)), function (err) {
				  if (err) return console.error(err);
					console.log(items.length);
				});
			});
		}).on('error', (err) => {
			console.log("Error: " + err.message);
		});
	});
})();