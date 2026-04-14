# 🏛️ MyCollegeVerse: Institutional Data Standard 🚀

To ensure that its searching and filtering engines (State, Type, Stream) function perfectly, the Multiverse requires a strict data format for both **Manual Initialization** and **Bulk Injection**.

---

## 🛰️ 1. The Bulk Injection Standard (Paste/CSV)
Whether you are using **Pathway A (CSV)** or **Pathway B (Paste)**, your data MUST contain exactly **9 columns** separated by the pipe symbol `|`.

### Column Order:
1. `Name`: Official Institution name.
2. `Type`: Primary classification (must match allowed types).
3. `State`: The state home of the node (must match allowed states).
4. `City`: The city coordinates.
5. `Streams`: **Comma-separated** academic sectors (e.g., Engineering, MBA).
6. `Location`: Detailed physical address.
7. `Description`: Institutional narrative/summary.
8. `LogoURL`: High-fidelity image link.
9. `Tags`: **Comma-separated** discovery tokens (e.g., DU, Tier1).

### 📝 Example (Paste this into the Terminal):
`Hindu College | Government | Delhi | New Delhi | Arts, Science, Commerce | North Campus, Delhi | One of India's most prestigious colleges. | https://logo.png | DU, Tier1`

---

## 🏛️ 2. Manual Initialization (The Registry Form)
When adding a college node through the UI, ensure you select the correct **Type** and **State** from the dropdowns. 

> [!IMPORTANT]
> **Streams** are multi-select checkboxes. You must select at least one stream for the search filters to register the college in that category.

---

## 📡 3. Allowed Keywords (Taxonomy)
For the filter to match, you must use these exact values (Case-Sensitive):

### 🏢 Institutional Types:
- `Private`, `Government`, `Semi-Government`, `State Government`, `Central University`, `Autonomous`, `Deemed University`

### 🗺️ State Hubs:
- `Delhi`, `Uttar Pradesh`, `Haryana`, `Maharashtra`, `Karnataka`, `Tamil Nadu`, `West Bengal`, etc. (Standard Indian States)

### 🎓 Academic Streams:
- `Engineering`, `MBBS`, `B.A`, `MBA`, `B.Com`, `BSc`, `BBA`, `B.Tech`, `M.Tech`, `BCA`, `MCA`, `Law`, `Pharmacy`, `Design`, `Architecture`, `Management`

---

## 🛡️ "Zero-Crash" Verification
- **Code Guard**: The system now automatically skips PHP code lines if accidentally pasted.
- **Auto-Sync**: Ratings and node calibrations are handled by the core registry; do not include ratings in your bulk data.
