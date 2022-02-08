
let lastSortedBy;

// Going to need a function to parse data from a table row
const parseData = (sortBy) => {
    // find the table element and all its rows
    let tableBody = document.getElementById("dyno-table-body")
    let rows = document.getElementsByClassName("dyno-table-row")

    // Since the rows object is behaving strangely (not obj, not array?)
    // quickly make a new array of the elements
    let rowArr = []
    for (let i = 0; i < rows.length; i++) {
        rowArr.push(rows[i])
    }

    // sort the new array by the dollar value (after converting it to an integer from $)
    rowArr.sort((a,b) => {
        let x = a.children[sortBy].innerHTML
        let y = b.children[sortBy].innerHTML
        // if the column is $$$
        if(x[0] === '$'){
            //format and sort for $$$
            return accounting.unformat(x) - accounting.unformat(y)
        }
        // if its a number
        if(parseInt(x)){
            return parseInt(x) - parseInt(y)
        }
        // Otherwise sort by string value
        // TODO - Should sorting by string ignore none, some, or all special characters?? Ask stakeholder
        return y.toLowerCase() < x.toLowerCase() ? 1 : -1
    })

    // if this row was previously sorted by this attribute, reverse the order
    if(lastSortedBy === sortBy){
        rowArr.reverse()
        // reset the lastSortedBy variable to enable toggling
        lastSortedBy = -1
    } else {
        lastSortedBy = sortBy
    }

    // set the table-body's innerHTML to the newly arranged rows
    let ht = ""
    rowArr.forEach(r => {
        ht += '<tr class="dyno-table-row">'+ r.innerHTML +'</tr>'
    })
    tableBody.innerHTML = ht
}


// Grab all the buttons in the document
let headers = document.getElementsByClassName('sortable')
// Add listeners to them based on their column index
for (let i = 0; i < headers.length; i++) {
    const header = headers[i];
    let x = [...header.parentNode.children].indexOf(header)
    header.addEventListener('click', function(){parseData(x)})
}
// TODO: make sure that only the columnsin the corresponding table get sorted.
// Otherwise, tables will have similar index values, and sorting the
// column 0 button will sort all tables by their column 0.
