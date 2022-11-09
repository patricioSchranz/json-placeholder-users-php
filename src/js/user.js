const 
    headerOne = document.querySelector('h1'),
    notCompletedListItems = document.querySelectorAll('.user_todo-section_list .not-completed')



headerOne.addEventListener('click', ()=>{
    window.open('../index.php', '_self')
})

if(notCompletedListItems){
    notCompletedListItems.forEach(item =>{
        item.addEventListener('click', ()=>{
            item.classList.remove('not-completed')
            item.classList.add('completed')
        })
    })
}
