function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});
    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table.display tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) {
            var t ;
            if(i==0){
                t = cols[j].children[0].innerText;
            } else{
                t = cols[j].innerText;
            }
            if(i != 0 && j == 0) {
                t = ':' + t;
            }
            row.push(t);
        }
        csv.push(row.join(","));
    }
    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}