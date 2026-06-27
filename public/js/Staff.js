let queueData = [
    { token: 101, name: "Sarah Connor", age: 34, source: "Online", time: 15, status: "Waiting" }
];

window.onload = renderQueue;

function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

// Show detailed positioning for a specific token
function showQueueDetails(token) {
    const index = queueData.findIndex(p => p.token === token);
    if (index === -1) return;

    // Calculate time before this specific person
    let timeBefore = 0;
    for (let i = 0; i < index; i++) {
        timeBefore += parseInt(queueData[i].time);
    }

    const detailHtml = `
        <p><strong>Patient:</strong> ${queueData[index].name}</p>
        <p><strong>Token Number:</strong> #${token}</p>
        <p><strong>Position in Line:</strong> ${index + 1}</p>
        <hr style="border: 0; border-top: 1px solid var(--bg); opacity: 0.2; margin: 15px 0;">
        <p style="font-size: 1.1rem;"><strong>Estimated Wait Before:</strong> <span style="color: #fff; background: var(--bg); padding: 2px 8px; border-radius: 4px;">${timeBefore} min</span></p>
    `;

    document.getElementById('detail-content').innerHTML = detailHtml;
    openModal('detailModal');
}

function submitPatient() {
    const name = document.getElementById('p_name').value;
    const age = document.getElementById('p_age').value;
    if (!name || !age) return alert("Please enter name and age.");

    const lastToken = queueData.length > 0 ? queueData[queueData.length - 1].token : 100;
    
    queueData.push({ 
        token: lastToken + 1, 
        name, 
        age, 
        source: "Physical", 
        time: 15, 
        status: "Waiting" 
    });
    
    closeModal('patientModal');
    document.getElementById('p_name').value = '';
    document.getElementById('p_age').value = '';
    renderQueue();
}

function toggleStatus(token) {
    const currentIndex = queueData.findIndex(x => x.token === token);
    const p = queueData[currentIndex];

    if (p.status === "Waiting") {
        p.status = "Serving";
    } else {
        queueData.splice(currentIndex, 1);
        const nextInLine = queueData.find(x => x.status === "Waiting");
        if (nextInLine) {
            nextInLine.status = "Serving";
        }
    }
    renderQueue();
}

function submitGlobalTime() {
    const newTime = document.getElementById('global_min').value;
    queueData.forEach(p => { if (p.status === "Waiting") p.time = parseInt(newTime); });
    closeModal('timeModal');
    renderQueue();
}

function renderQueue() {
    const tbody = document.getElementById('queue-body');
    tbody.innerHTML = '';

    queueData.forEach(p => {
        const row = `<tr>
            <td><span class="token-id" onclick="showQueueDetails(${p.token})">#${p.token}</span></td>
            <td>${p.name} <br><small>Age: ${p.age}</small></td>
            <td>${p.source}</td>
            <td>${p.time} min</td>
            <td><span style="color: ${p.status === 'Serving' ? '#00d4ff' : 'inherit'}">${p.status}</span></td>
            <td><button class="btn-queue" onclick="toggleStatus(${p.token})">${p.status === 'Waiting' ? 'Start Serving' : 'Finish'}</button></td>
        </tr>`;
        tbody.innerHTML += row;
    });
    updateStats();
}

function updateStats() {
    document.getElementById('stat-total').innerText = queueData.length;
    const serving = queueData.find(p => p.status === 'Serving');
    document.getElementById('stat-serving').innerText = serving ? `#${serving.token}` : '--';
    const totalTime = queueData.reduce((sum, p) => sum + parseInt(p.time), 0);
    document.getElementById('stat-avg-time').innerText = totalTime + "m";
}