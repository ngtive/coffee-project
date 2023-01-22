$(document).ready(function() {
    let button = $('button.plyr__controls__item.plyr__control');
    let svg_not_pressed = `
<svg xmlns="http://www.w3.org/2000/svg" width="50.722" height="45.723" viewBox="0 0 37.722 37.723" class="icon--not-pressed">
  <g id="Group_84" data-name="Group 84" transform="translate(-1256.478 -4140)">
    <path id="Path_247" data-name="Path 247" d="M187.353,132.541c0,2.039-1.5,3.193-3.347,3.58-1.606.336-2.566,1.367-3.708,2.446-.827.781-2.1,1.51-3.267,1.06a2.678,2.678,0,0,1-1.479-2.477,21.015,21.015,0,0,1,.338-2.7,17.92,17.92,0,0,0,.063-2.826c-.085-1.485-.728-3.106-.226-4.558a2.092,2.092,0,0,1,2.56-1.483l.051.014a5.828,5.828,0,0,1,2.688,1.879,6.392,6.392,0,0,0,3.3,1.775,3.423,3.423,0,0,1,3,2.945c.022.154.025.311.027.345" transform="translate(1094.916 4025.491)" fill="#f17c13" stroke="rgba(0,0,0,0)" stroke-width="1"/>
    <path id="Path_248" data-name="Path 248" d="M16.361,0A16.361,16.361,0,1,1,0,16.361,16.361,16.361,0,0,1,16.361,0Z" transform="translate(1258.978 4142.5)" fill="none" stroke="#f17c13" stroke-width="5"/>
  </g>
</svg>
`;
    let svg_pressed = `
    
<svg id="Layer_1" data-name="Layer 1" width="50.722" height="45.723" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37.72 37.72" class="icon--pressed" 
    ><defs><style>.cls-1,.cls-2{fill:none;stroke:#f17c13;}.cls-1{stroke-width:5px;}.cls-2{stroke-linecap:round;stroke-width:4px;}</style>
    </defs><g id="Group_84" data-name="Group 84"><path id="Path_248" data-name="Path 248" class="cls-1" 
    d="M18.85,2.5A16.36,16.36,0,1,1,2.48,18.86,16.37,16.37,0,0,1,18.85,2.5Z" transform="translate(0.02 0)"/>
    <line class="cls-2" x1="14.65" y1="13.73" x2="14.65" y2="23.99"/><line class="cls-2" x1="23.07" y1="13.73" x2="23.07" y2="23.99"/></g></svg>
    `;

    button.html(`
${svg_not_pressed}
${svg_pressed}
<span class="label--pressed plyr__sr-only">Pause</span>
<span class="label--not-pressed plyr__sr-only">Play</span>`)


});