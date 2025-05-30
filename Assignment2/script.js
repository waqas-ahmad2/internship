// search bar function
function search(){
    search = document.getElementById('search')
    rows = document.querySelectorAll('table tbody tr')

    search.addEventListener("input",function(){
    searchtext = this.value.toLowerCase()
    rows.forEach(row => {
        if(row.innerText.toLowerCase().includes(searchtext)){
            row.style.display = ''
        }
        else{
            row.style.display = 'none'
        }
        });
    })
}

function password_show(){
    eye = document.getElementById('eye_icon')
    eyelogo = document.getElementById('eyelogo')
    
    pass_field = document.getElementById('password')
    eye.addEventListener('click',()=>{
        if(pass_field.getAttribute('type')==='text'){
            pass_field.setAttribute('type',"password")
            eyelogo.classList.remove("fa-eye-slash")
            eyelogo.classList.add("fa-eye")

        }
        else if(pass_field.getAttribute('type')==='password'){
            pass_field.setAttribute('type',"text")
            eyelogo.classList.remove("fa-eye")
            eyelogo.classList.add("fa-eye-slash")
        }
    })
}




function OpenModal(str){

    let modal = document.getElementById(`StudentsModal`);
    let update = document.getElementById(`modalUpdateButton`);
    let submit = document.getElementById(`modalSubmitButton`);
    let titleAdd = document.getElementById(`modalTitleAdd`);
    let titleUpdate = document.getElementById(`modalTitleUpdate`);

    // Reset all displays first
    update.style.display = 'block'
    titleUpdate.style.display = 'block'
    submit.style.display = 'block'
    titleAdd.style.display = 'block'
    modal.style.display = 'block'

    // Show appropriate elements based on mode
    if(str === 'add_stud'){
        update.style.display = 'none'
        titleUpdate.style.display = 'none'
    }
    if(str === 'edit_stud'){
        submit.style.display = 'none'
        titleAdd.style.display = 'none'
    }


}

function CloseModal(){
    document.getElementById('StudentsModal').style.display = 'none'
}


function OpenVoucherModal(str) {
    let modal = document.getElementById('VoucherModal')
    let submit = document.getElementById('modalSubmitButton')
    let titleAdd = document.getElementById('modalTitleAdd')


    // Reset all displays first
    submit.style.display = 'none'
    titleAdd.style.display = 'none'
    modal.style.display = 'block'

    if(str === 'add_voucher') {
        submit.style.display = 'block'
        titleAdd.style.display = 'block'
    }
}

function CloseVoucherModal() {
    document.getElementById('VoucherModal').style.display = 'none'
}


function CloseReceiptModal() {
    const modal = document.getElementById('ReceiptModal');
    modal.style.display = 'none';
}

// Function to update the total amount after deleting a record
function updateTotalAmount() {
    let total = 0;
    const rows = document.querySelectorAll('#receiptDetails tr');
    rows.forEach(row => {
        const amountCell = row.querySelector('td:nth-child(2)');
        if (amountCell) {
            total += parseFloat(amountCell.textContent) || 0;
        }
    });
    document.getElementById('amt_sum').textContent = total.toFixed(2);
}

let editedEntries = [];
let deletedEntries = [];
let addedEntries = [];

function OpenReceiptModal(str) {
    const modal = document.getElementById('ReceiptModal')
    const title = document.getElementById('receiptModalTitle')
    const saveButton = document.getElementById('saveButton')
    const updateButton = document.getElementById('updateButton')
    const printButton = document.getElementById('printButton')
    const voucherField = document.getElementById('receiptVoucherId')
    
    voucherField.disabled = true;


    // Set modal title and button visibility based on mode
    if (str === 'view') {
        addEntryBtn = document.getElementById('addEntryBtn').classList.add('d-none');
        updateButton.classList.add('d-none');
        title.textContent = 'Fee Invoice'
        saveButton.classList.remove('d-none')
        printButton.classList.remove('d-none')

    }
    else if (str === 'edit') {
        addEntryBtn = document.getElementById('addEntryBtn').classList.remove('d-none');
        updateButton.classList.remove('d-none');
        title.textContent = 'Edit Invoice';
        saveButton.classList.add('d-none');
        printButton.classList.add('d-none');

        editedEntries = [];
        deletedEntries = [];

    }
    modal.style.display = 'block';

}

function addNewEntryRow() {
    const tbody = document.getElementById('receiptDetails');

    // Create a new row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" class="form-control new-description" placeholder="Description" required></td>
      <td><input type="number" class="form-control new-amount" placeholder="Amount" required></td>
      <td>
        <a href="#" class="text-decoration-none me-2 text-primary" onclick="saveNewEntry(this)"><i class="far fa-save"></i></a>
        <a href="#" class="text-decoration-none text-danger" onclick="cancelNewEntry(this)"><i class="fa-solid fa-xmark"></i></a>
      </td>
    `;
    tbody.append(newRow);
}
  
function saveNewEntry(button) {
    const row = button.closest('tr');
    const description = row.querySelector('.new-description').value.trim();
    const amount = parseFloat(row.querySelector('.new-amount').value.trim());
    const StudentId = parseInt(document.getElementById('receiptStudentId').value);

    if (!description || isNaN(amount)) {
        alert("Please fill in valid description and amount.");
        return;
    }

    // Generate a unique ID for the new row
    const uniqueId = `new-${Date.now()}`;

    // Update the table row
    row.setAttribute('data-id', uniqueId); // Assign the unique ID to the row
    row.innerHTML = `
        <td>${description}</td>
        <td>${amount.toFixed(2)}</td>
        <td>
        <a id='new_row_edit' href='#' class='text-primary me-2 text-decoration-none'>
            <i class='fas fa-edit edit_entry'></i>
        </a>
        <a id='new_row_del' href='#' class='text-danger text-decoration-none'>
            <i class='fas fa-trash-alt del_entry'></i>
        </a>
        </td>
    `;

    // Add the new entry to the addedEntries array
    addedEntries.push({
        id: uniqueId,
        student_id: StudentId,
        amount: amount,
        description: description
    });

    updateTotalAmount();
}

function cancelNewEntry(button) {
    const row = button.closest('tr');
    row.remove();
}

//print pdf code
function generatePDF() {
    const modal = document.getElementById('modal_content');
    const footer = modal.querySelector('.modal-footer');

    const options = {
      margin:       0.5,
      filename:     'document.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 1.5 , useCORS: true },
      jsPDF:        { unit: 'mm', format: 'A4', orientation: 'portrait' }
    };

    modal.classList.add('print-mode');
    html2pdf().set(options).from(modal).save().then(() => {
        modal.classList.remove('print-mode');
    });

    CloseReceiptModal()
}

search()

document.addEventListener("DOMContentLoaded", ()=>{
    password_show()
});

    let tbody = document.getElementById('bubble');
    tbody.addEventListener('click', (e) => {
        const edit_stud_Btn = e.target.closest(".edit_stud");
        const del_stud_Btn = e.target.closest(".del_stud");
        const del_entry_Btn = e.target.closest(".del_entry");
        const view_entry_Btn = e.target.closest(".view_entry");
        const edit_entry_Btn = e.target.closest(".edit_entry");

        if(edit_stud_Btn) {
            stud_id = edit_stud_Btn.getAttribute('data-studedit')
            fetch(`server.php?action=getStudent&id=${stud_id}`)
            .then(res=>res.json())
            .then(data=>{
                if(data.error){
                    alert("Error Fetching Data")
                }
                else{
                    document.getElementById('studentId').value = data.id;
                    document.getElementById('studentName').value = data.name;
                    document.getElementById('studentEmail').value = data.email;
                    document.getElementById('studentAge').value = data.age;
                    document.getElementById('studentForm').action = 'server.php?action=editStudent';
                }
                OpenModal('edit_stud');
            })
            .catch(error => console.error('Error:', error));
        }
        
        if(del_stud_Btn) {
            stud_id = del_stud_Btn.getAttribute('data-studdel')
            if (confirm('Are you sure you want to delete this student?')) {
                fetch(`server.php?action=deleteStudent&id=${stud_id}`)
                .then(res=>res.text())
                .then(data=>{
                    window.location.href = "student.php";       
                })
            }
        }

        if(edit_entry_Btn){
            vouch_id = edit_entry_Btn.getAttribute('data-vouchedit')
            receiptDetails = document.getElementById('receiptDetails')
            receiptStudentEmail = document.getElementById('receiptStudentEmail')
            receiptStudentName = document.getElementById('receiptStudentName')
            receiptStudentId = document.getElementById('receiptStudentId')
            receiptVoucherId = document.getElementById('receiptVoucherId')
            amt_sum_field = document.getElementById('amt_sum')
            entry_action = document.getElementById('edit_entry_th_action')
            serialNumber = document.getElementById('Serial_number')

            serialNumber.classList.add('d-none')
            entry_action.classList.remove('d-none')

            fetch(`server.php?action=getrecord&id=${vouch_id}`)
            .then(res=>res.json())
            .then(data=>{
                if(data.error){
                    alert("Error Fetching Data")
                }
                else{
                    amt_sum = 0
                    receiptStudentName.value = data[0]['name']
                    receiptStudentId.value = data[0]['std_id']
                    receiptStudentEmail.value = data[0]['email']
                    receiptVoucherId.value = data[0]['voucher_id']
                    
                    receiptDetails.innerHTML =''
                    data.forEach(row=>{
                        amt_sum += parseFloat(row['amount'])
                        receiptDetails.innerHTML +=
                        `<tr>
                            <td>${row['description']}</td>
                            <td>${row['amount']}</td>
                            <td>
                                <a href='#' class='text-primary me-2 text-decoration-none'>
                                    <i data-entryedit=${row['detail_id']} class='fas fa-edit edit_entry'></i>
                                </a>

                                <a href='#'  class='text-danger text-decoration-none'>
                                    <i data-entrydel=${row['detail_id']} class='fas fa-trash-alt del_entry'></i>
                                </a>
                            </td>
                         </tr>`

                    })
                }
                amt_sum_field.innerHTML = amt_sum
                OpenReceiptModal('edit')
                
            })
            .catch(error => console.error('Error:', error));        
        }

        if(view_entry_Btn){
            vouch_id = view_entry_Btn.getAttribute('data-vouchedit')
            receiptDetails = document.getElementById('receiptDetails')
            receiptStudentEmail = document.getElementById('receiptStudentEmail')
            receiptStudentName = document.getElementById('receiptStudentName')
            receiptVoucherId = document.getElementById('receiptVoucherId')
            amt_sum_field = document.getElementById('amt_sum')
            entry_action = document.getElementById('edit_entry_th_action')
            serialNumber = document.getElementById('Serial_number')

            serialNumber.classList.remove('d-none')
            entry_action.classList.add('d-none')

            fetch(`server.php?action=getrecord&id=${vouch_id}`)
            .then(res=>res.json())
            .then(data=>{
                if(data.error){
                    alert("Error Fetching Data")
                }
                else{
                    count = 0
                    amt_sum = 0
                    receiptStudentName.value = data[0]['name']
                    receiptStudentEmail.value = data[0]['email']
                    receiptVoucherId.value = data[0]['voucher_id']
                    
                    receiptDetails.innerHTML =''
                    data.forEach(row=>{
                        count+=1
                        amt_sum += parseFloat(row['amount'])
                        receiptDetails.innerHTML +=
                        `<tr>
                            <td>${count}</td>
                            <td>${row['description']}</td>
                            <td>${row['amount']}</td>
                         </tr>`

                    })
                }
                amt_sum_field.innerHTML = amt_sum
                OpenReceiptModal('view')
                
            })
            .catch(error => console.error('Error:', error));        


        }

        if(del_entry_Btn){
            vouchdel = del_entry_Btn.getAttribute('data-vouchdel')
            if (confirm('Are you sure you want to delete this?')) {
                fetch(`server.php?action=deleterecord&id=${vouchdel}`)
                .then(res=>res.text())
                .then(data=>{
                    window.location.href = "detail.php";       
                })
            }
        }

    });

    // for detail records    
    innertbody = document.getElementById("receiptDetails")
    innertbody.addEventListener('click', (e) => {
        const inner_del_entry_Btn = e.target.closest(".del_entry");
        const inner_edit_entry_Btn = e.target.closest(".edit_entry");

        if (inner_del_entry_Btn) {
            const row = inner_del_entry_Btn.closest('tr');
            const entryId = inner_del_entry_Btn.getAttribute('data-entrydel'); // This will be `null` for new rows.
        
            if (!entryId) {
                // Remove the entry from `addedEntries` if it's a new row
                const addedEntryIndex = addedEntries.findIndex(entry => entry.id === row.getAttribute('data-id'));
                if (addedEntryIndex !== -1) {
                    addedEntries.splice(addedEntryIndex, 1);
                }
            } else {
                // Add the entry ID to the `deletedEntries` array for existing rows
                deletedEntries.push(entryId);
            }
        
            // Remove the row from the UI
            row.remove();
        
            // Update the total amount
            updateTotalAmount();
        }

        if (inner_edit_entry_Btn) {
            const row = inner_edit_entry_Btn.closest('tr');
            const descriptionCell = row.querySelector('td:nth-child(1)');
            const amountCell = row.querySelector('td:nth-child(2)');
            const entryId = inner_edit_entry_Btn.getAttribute('data-entryedit'); // This will be `null` for new rows.
        
            if (inner_edit_entry_Btn.classList.contains('fa-edit')) {
                const currentDescription = descriptionCell.textContent.trim();
                const currentAmount = amountCell.textContent.trim();
        
                descriptionCell.innerHTML = `<input type="text" class="form-control edit-description" value="${currentDescription}" required>`;
                amountCell.innerHTML = `<input type="number" class="form-control edit-amount" value="${currentAmount}" required>`;
        
                inner_edit_entry_Btn.classList.remove('fa-edit');
                inner_edit_entry_Btn.classList.add('fa-save');
                inner_edit_entry_Btn.setAttribute('title', 'Save Changes');
            } else if (inner_edit_entry_Btn.classList.contains('fa-save')) {
                const updatedDescription = row.querySelector('.edit-description').value.trim();
                const updatedAmount = parseFloat(row.querySelector('.edit-amount').value.trim());
        
                if (!updatedDescription || isNaN(updatedAmount)) {
                    alert('Please provide valid values for description and amount.');
                    return;
                }
        
                descriptionCell.textContent = updatedDescription;
                amountCell.textContent = updatedAmount.toFixed(2);
        
                inner_edit_entry_Btn.classList.remove('fa-save');
                inner_edit_entry_Btn.classList.add('fa-edit');
                inner_edit_entry_Btn.setAttribute('title', 'Edit Entry');
        
                if (!entryId) {
                    // Update the `addedEntries` array for new rows
                    const addedEntryIndex = addedEntries.findIndex(entry => entry.id === row.getAttribute('data-id'));
                    if (addedEntryIndex !== -1) {
                        addedEntries[addedEntryIndex].description = updatedDescription;
                        addedEntries[addedEntryIndex].amount = updatedAmount;
                    }
                } else {
                    // Update the `editedEntries` array for existing rows
                    editedEntries.push({
                        id: entryId,
                        description: updatedDescription,
                        amount: updatedAmount
                    });
                }
        
                updateTotalAmount();
            }
        }
    })

    //Update button function

    document.getElementById('updateButton').addEventListener('click', () => {
        if (editedEntries.length === 0 && deletedEntries.length === 0 && addedEntries.length === 0) {
            alert('No changes to save.');
            return;
        }
    
        fetch('server.php?action=updateEntries', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                voucherId: parseInt(document.getElementById('receiptVoucherId').value),
                editedEntries: editedEntries,
                addedEntries: addedEntries,
                deletedEntries: deletedEntries
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Changes saved successfully.');
                    CloseReceiptModal()
                    window.location.reload();
                } else {
                    alert('Error saving changes: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));

    });

    table_body.addEventListener('click', (e) => {
        add_row_btn = e.target.closest('.add_row');
        del_row_btn = e.target.closest('.del_row');

        if (add_row_btn) {
            e.target.classList.add('d-none');
            e.target.nextElementSibling.classList.remove('d-none');

            newRow = document.createElement('tr');
            newRow.className = 'mb-3 form-group';
            newRow.innerHTML = `
                <td><input class="form-control" name="description[]" type="text" required></td>
                <td><input class="form-control" name="amount[]" type="number" required></td>
                <td>
                    <button type="button" class="btn btn-warning add_row">Add</button>
                    <button type="button" class="btn btn-danger d-none del_row">Delete</button>
                </td>
            `;
            table_body.appendChild(newRow);
        }

        if (del_row_btn) {
            del_row_btn.closest('tr').remove();
        }

        
});


// Close modal 
window.onclick = function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
}
