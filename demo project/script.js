function fetchphp(){
    fetch(`api.php`)
    .then(res => res.json())
    .then(data => {
        if (data.length === 0) {
            display.innerHTML = `<p>No data found.</p>`;
            return;
        }
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
                    <input type="hidden" name="name" value="${row['name']}">
                    <input type="hidden" name="email" value="${row['email']}">
                    <input type="hidden" name="age" value="${row['age']}">
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

function OpenModal(type){
    let modal = document.getElementById(`StudentsModal`);
    let update = document.getElementById(`modalUpdateButton`);
    let submit = document.getElementById(`modalSubmitButton`);
    let titleAdd = document.getElementById(`modalTitleAdd`);
    let titleUpdate = document.getElementById(`modalTitleUpdate`);

    if(type ==="add"){
        update.style.display = 'none'
        modal.style.display = 'block'
        titleUpdate.style.display = 'none'

    }
    else if(type === "edit"){
        modal.style.display = 'block'
        submit.style.display = 'none'
        titleAdd.style.display = 'none'
    }

}

document.addEventListener("DOMContentLoaded", () => {
    let display = document.getElementById('display');
    let addbtn = document.getElementById('addbtn');

    fetchphp();
    addbtn.addEventListener('click', () => {
        OpenModal("add")
    })

    display.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            const form = editBtn.closest('form');
            const formData = new FormData(form);

            OpenModal("edit");
            
            document.getElementById('studentId').value = formData.get('id');
            document.getElementById('studentName').value = formData.get('name');
            document.getElementById('studentEmail').value = formData.get('email');
            document.getElementById('studentAge').value = formData.get('age');
    
        }
            
        else{
                alert('Failed to fetch student data.');
            };
        });


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
