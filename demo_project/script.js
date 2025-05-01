function fetchphp(){
    fetch(`api.php`)
    .then(res => res.json())
    .then(data => {
        if (data.length === 0) {
            display.innerHTML = `<p>No data found.</p>`;
            return;
        }
        display.innerHTML = `<h2>Students</h2>`
        let headers = Object.keys(data[0]);
        let table = `<table class="table table-hover m-auto"><thead><tr>`;

        headers.forEach(header => {
            table += `<th>${header}</th>`;
        });
        
        // action column for action buttons
        table += `<th>Action</th>
                    </tr>
                    </thead><tbody>`;
                
        data.forEach(row => {
            table += `<tr>`;
            headers.forEach(header => {
                table += `<td>${row[header]}</td>`;
            });

            // for adding icons for edit and delete in each row
            table += `<td>
                    <form method="POST" action="server.php">
                    <input type="hidden" name="id" value="${row['id']}">
                    <button class="edit-btn"  name="edit"><i class="fas fa-edit"></i></button>
                    <button class="delete-btn" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    </td></tr>`;
        });

        table += `</tbody></table>`;
        display.innerHTML += table;
    })
    .catch(err => {
        display.innerHTML = `<p>Error fetching data</p>`;
        console.error(err);
    });
}

function closeModal(){
    let modal = document.getElementById('StudentsModal')
    modal.style.display = 'none'
}

function OpenModal(){
    let modal = document.getElementById(`StudentsModal`);
    modal.style.display = 'block';

}

document.addEventListener("DOMContentLoaded", () => {
    let display = document.getElementById('display');
    let addbtn = document.getElementById('addbtn');

    fetchphp();
    addbtn.addEventListener('click', () => {
        OpenModal()
    })

    // Close modal 
    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            }
        }

})

    //search bar
    let search = document.getElementById('searchbar')
    search.addEventListener('input',(e)=>{
        let text = e.target.value.toLowerCase()
        let all_data = document.querySelectorAll('#display table tbody tr')

        all_data.forEach(row=>{
            let rowtext = row.innerText.toLowerCase()
            row.style.display = rowtext.includes(text)?'':'none'

        })

    })
