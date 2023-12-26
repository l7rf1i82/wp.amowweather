function json(url) {
    return fetch(url).then(res => res.json());
}

const gmt  = moment().utcOffset() / 60;
const time = moment().format('YYYY-MM-DD, h:mm:ss a') + ', GMT' + Math.floor(gmt);

json(`${public_action_data.url}?action=${public_action_data.action}&time=${time}`).then(data => {
    if(!public_action_data.toggle_public_widget) { return; }
    const html = `
        <div class="amoweather-widget">
            <span>${data.location}</span>,&nbsp;<span>${data.temperature}</span>
        </div>
    `;
    const node = document.createElement('div');
          node.innerHTML = html;

    document.body.appendChild(node);
});
